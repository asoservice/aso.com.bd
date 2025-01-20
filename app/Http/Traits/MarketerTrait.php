<?php

namespace App\Http\Traits;

use App\Models\{
    MarketerUser,
    MarketerTransaction,
    CommissionDistribution,
    MarketerTier,
    User,
    Campaign,
    MarketerSetting,
    MarketerWallet,
    MarketerWithdrawRequest,
    CampaignAnalytics,
    MarketerLink,
    ClickTracking,
    ConversionTracking
};
use Illuminate\Support\Facades\{DB, Log};
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;

trait MarketerTrait
{
    /**
     * Initialize the marketer settings
     * @return MarketerSetting
     */
    private function getMarketerSetting(): MarketerSetting
    {
        return MarketerSetting::firstOrCreate(
            [],
            [
                'min_withdrawal_amount' => 100.00,
                'min_earning_requirement' => 100.00,
                'commission_waiting_days' => 30,
                'is_active' => true,
                'max_marketer_level' => 3
            ]
        );
    }

    /**
     * Register a new marketer
     * @param User $user
     * @param string|null $parentReferralCode
     * @return MarketerUser
     * @throws Exception
     */
    public function registerMarketer(User $user, ?string $parentReferralCode = null): MarketerUser
    {
        return DB::transaction(function () use ($user, $parentReferralCode) {
            $settings = $this->getMarketerSetting();
            $parentMarketer = null;
            $level = 1;

            if ($parentReferralCode) {
                $parentMarketer = MarketerUser::where('referral_code', $parentReferralCode)->first();
                if ($parentMarketer) {
                    $level = $parentMarketer->level + 1;
                    if ($level > $settings->max_marketer_level) {
                        throw new Exception("Maximum marketer level of {$settings->max_marketer_level} reached");
                    }
                }
            }

            $marketerUser = MarketerUser::create([
                'user_id' => $user->id,
                'referral_code' => $this->generateUniqueReferralCode(),
                'parent_id' => $parentMarketer?->id,
                'level' => $level,
                'status' => 'pending',
                'total_earned' => 0,
                'last_30_days_earnings' => 0
            ]);

            MarketerWallet::create([
                'marketer_id' => $marketerUser->id,
                'balance' => 0
            ]);

            return $marketerUser;
        });
    }

    /**
     * Create a new campaign
     */
    public function createCampaign(MarketerUser $marketer, array $data): Campaign
    {
        return DB::transaction(function () use ($marketer, $data) {
            $this->validateCampaignData($data);

            return Campaign::create([
                'marketer_id' => $marketer->id,
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'target_amount' => $data['target_amount'] ?? null,
                'commission_rate' => $data['commission_rate'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
                'tracking_code' => $this->generateUniqueTrackingCode(),
                'landing_page_url' => $data['landing_page_url'] ?? null,
                'target_demographics' => $data['target_demographics'] ?? null,
                'status' => $data['status'] ?? 'draft'
            ]);
        });
    }

    /**
     * Record campaign visit
     */
    public function recordCampaignVisit(Campaign $campaign, array $visitorData = []): void
    {
        DB::transaction(function () use ($campaign, $visitorData) {
            $analytics = CampaignAnalytics::firstOrNew([
                'campaign_id' => $campaign->id,
                'date' => now()->toDateString()
            ]);

            $analytics->daily_visits = ($analytics->daily_visits ?? 0) + 1;

            if (!empty($visitorData['visitor_id']) || !empty($visitorData['ip'])) {
                $uniqueIdentifier = $visitorData['visitor_id'] ?? $visitorData['ip'];
                $uniqueKey = "campaign:{$campaign->id}:visitors:" . now()->toDateString();

                if (!Redis::sismember($uniqueKey, $uniqueIdentifier)) {
                    Redis::sadd($uniqueKey, $uniqueIdentifier);
                    Redis::expire($uniqueKey, 86400);
                    $analytics->unique_visitors = ($analytics->unique_visitors ?? 0) + 1;
                }
            }

            $this->updateAnalyticsMetrics($analytics, $visitorData);
            $analytics->save();

            $campaign->increment('visits');
            $campaign->updateAnalytics();
        });
    }

    private function updateAnalyticsMetrics(CampaignAnalytics $analytics, array $visitorData): void
    {
        if (!empty($visitorData['device_type'])) {
            $deviceBreakdown = $analytics->device_breakdown ?? [];
            $deviceBreakdown[$visitorData['device_type']] = ($deviceBreakdown[$visitorData['device_type']] ?? 0) + 1;
            $analytics->device_breakdown = $deviceBreakdown;
        }

        if (!empty($visitorData['source'])) {
            $trafficSources = $analytics->traffic_sources ?? [];
            $trafficSources[$visitorData['source']] = ($trafficSources[$visitorData['source']] ?? 0) + 1;
            $analytics->traffic_sources = $trafficSources;
        }

        if ($analytics->daily_visits > 0) {
            $analytics->conversion_rate = ($analytics->approved_orders / $analytics->daily_visits) * 100;
        }
    }


    /**
     * Record a new transaction and distribute commissions
     */
    public function recordTransaction(
        MarketerUser $marketer,
        float $amount,
        string $orderId,
        ?Campaign $campaign = null,
        string $type = 'sale'
    ): MarketerTransaction {
        return DB::transaction(function () use ($marketer, $amount, $orderId, $campaign, $type) {
            $transaction = MarketerTransaction::create([
                'marketer_id' => $marketer->id,
                'campaign_id' => $campaign?->id,
                'order_id' => $orderId,
                'amount' => $amount,
                'commission_earned' => 0,
                'status' => 'pending',
                'transaction_type' => $type
            ]);

            $this->distributeCommissions($transaction, $campaign);

            if ($campaign) {
                $this->updateCampaignAnalytics($campaign, $transaction);
            }

            return $transaction->fresh();
        });
    }

    /**
     * Distribute commissions to parent marketers
     * @param MarketerTransaction $transaction
     * @param Campaign|null $campaign
     * @return void
     */
    private function distributeCommissions(MarketerTransaction $transaction, ?Campaign $campaign = null): void
    {
        $settings = $this->getMarketerSetting();
        $marketer = $transaction->marketer;
        $currentParent = $marketer->parent;
        $level = 1;
        $totalCommission = 0;

        while ($currentParent && $level <= $settings->max_marketer_level) {
            if ($this->calculateLast30DaysEarnings($currentParent) >= $settings->min_earning_requirement) {
                $commissionAmount = $this->calculateCommissionAmount($transaction, $campaign, $level);

                $this->createCommissionDistribution(
                    $transaction,
                    $marketer,
                    $currentParent,
                    $level,
                    $commissionAmount
                );

                $totalCommission += $commissionAmount;
                $this->updateMarketerEarnings($currentParent, $commissionAmount);
            }

            $currentParent = $currentParent->parent;
            $level++;
        }

        $transaction->update([
            'commission_earned' => $totalCommission,
            'status' => 'approved'
        ]);
    }

    private function createCommissionDistribution(
        MarketerTransaction $transaction,
        MarketerUser $marketer,
        MarketerUser $parentMarketer,
        int $level,
        float $commissionAmount
    ): void {
        CommissionDistribution::create([
            'transaction_id' => $transaction->id,
            'marketer_id' => $marketer->id,
            'parent_marketer_id' => $parentMarketer->id,
            'level' => $level,
            'percentage' => $this->getCommissionRate($level),
            'amount' => $commissionAmount,
            'status' => 'pending'
        ]);
    }

    private function getCommissionRate(int $level): float
    {
        return MarketerTier::where('level', $level)->value('percentage') ?? 10.0;
    }

    /**
     * Process withdrawal request
     */
    public function processWithdrawal(
        MarketerUser $marketer,
        float $amount,
        string $paymentMethod,
        array $paymentDetails
    ): MarketerWithdrawRequest {
        try {
            return DB::transaction(function () use ($marketer, $amount, $paymentMethod, $paymentDetails) {
                $settings = $this->getMarketerSetting();
                $wallet = $marketer->wallet;

                if ($amount < $settings->min_withdrawal_amount) {
                    throw new Exception("Minimum withdrawal amount is {$settings->min_withdrawal_amount}");
                }

                if ($wallet->balance < $amount) {
                    throw new Exception('Insufficient balance');
                }

                $withdrawal = MarketerWithdrawRequest::create([
                    'marketer_id' => $marketer->id,
                    'amount' => $amount,
                    'payment_method' => $paymentMethod,
                    'payment_details' => $paymentDetails,
                    'status' => 'pending',
                    'processed_at' => null
                ]);

                $wallet->update([
                    'balance' => $wallet->balance - $amount
                ]);

                return $withdrawal;
            });
        } catch (Exception $e) {
            Log::error('Failed to process withdrawal:', [
                'error' => $e->getMessage(),
                'marketer_id' => $marketer->id
            ]);
            throw $e;
        }
    }

    /**
     * Update campaign analytics
     */
    private function updateCampaignAnalytics(Campaign $campaign, MarketerTransaction $transaction): void
    {
        $analytics = CampaignAnalytics::firstOrNew([
            'campaign_id' => $campaign->id,
            'date' => now()->toDateString()
        ]);

        $analytics->approved_orders = ($analytics->approved_orders ?? 0) + 1;
        $analytics->daily_commission = ($analytics->daily_commission ?? 0) + $transaction->commission_earned;

        if ($analytics->daily_visits > 0) {
            $analytics->conversion_rate = ($analytics->approved_orders / $analytics->daily_visits) * 100;
        }

        $analytics->save();

        $campaign->increment('approved_orders');
        $campaign->total_commission = $campaign->transactions()->sum('commission_earned');
        $campaign->conversion_rate = $campaign->visits > 0
            ? ($campaign->approved_orders / $campaign->visits) * 100
            : 0;
        $campaign->save();
    }

    /**
     * Update marketer tiers
     */
    public function updateMarketerTiers(array $tiers): void
    {
        try {
            DB::transaction(function () use ($tiers) {
                foreach ($tiers as $level => $percentage) {
                    MarketerTier::updateOrCreate(
                        ['level' => $level],
                        [
                            'percentage' => $percentage,
                            'minimum_earnings' => $this->getMarketerSetting()->min_earning_requirement
                        ]
                    );
                }
            });
        } catch (Exception $e) {
            Log::error('Failed to update commission tiers:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Update marketer settings
     */
    public function updateMarketerSetting(array $settings): MarketerSetting
    {
        try {
            return DB::transaction(function () use ($settings) {
                return tap($this->getMarketerSetting())->update($settings);
            });
        } catch (Exception $e) {
            Log::error('Failed to update marketer settings:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Get campaign analytics
     */
    public function getCampaignAnalytics(Campaign $campaign): array
    {
        $analytics = $campaign->analytics()
            ->select(
                DB::raw('SUM(daily_visits) as total_visits'),
                DB::raw('SUM(unique_visitors) as total_unique_visitors'),
                DB::raw('SUM(approved_orders) as total_orders'),
                DB::raw('SUM(daily_commission) as total_commission'),
                DB::raw('AVG(conversion_rate) as avg_conversion_rate'),
                DB::raw('AVG(bounce_rate) as avg_bounce_rate'),
                DB::raw('AVG(average_time_spent) as avg_time_spent')
            )
            ->first();

        return [
            'total_visits' => $analytics->total_visits ?? 0,
            'total_unique_visitors' => $analytics->total_unique_visitors ?? 0,
            'total_orders' => $analytics->total_orders ?? 0,
            'total_commission' => $analytics->total_commission ?? 0,
            'conversion_rate' => $analytics->avg_conversion_rate ?? 0,
            'bounce_rate' => $analytics->avg_bounce_rate ?? 0,
            'average_time_spent' => $analytics->avg_time_spent ?? 0,
            'traffic_sources' => $this->aggregateTrafficSources($campaign->analytics),
            'device_breakdown' => $this->aggregateDeviceBreakdown($campaign->analytics),
            'performance_by_date' => $this->getPerformanceByDate($campaign)
        ];
    }

    /**
     * Get detailed campaign analytics by date range
     */
    public function getCampaignAnalyticsByDateRange(Campaign $campaign, string $startDate, string $endDate): array
    {
        try {
            $analytics = $campaign->analytics()
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            return [
                'daily_stats' => $analytics->map(function ($day) {
                    return [
                        'date' => $day->date,
                        'visits' => $day->daily_visits,
                        'unique_visitors' => $day->unique_visitors,
                        'orders' => $day->approved_orders,
                        'commission' => $day->daily_commission,
                        'conversion_rate' => $day->conversion_rate,
                        'bounce_rate' => $day->bounce_rate,
                        'average_time_spent' => $day->average_time_spent
                    ];
                }),
                'totals' => [
                    'visits' => $analytics->sum('daily_visits'),
                    'unique_visitors' => $analytics->sum('unique_visitors'),
                    'orders' => $analytics->sum('approved_orders'),
                    'commission' => $analytics->sum('daily_commission'),
                    'average_conversion_rate' => $analytics->avg('conversion_rate'),
                    'average_bounce_rate' => $analytics->avg('bounce_rate'),
                    'average_time_spent' => $analytics->avg('average_time_spent')
                ]
            ];
        } catch (Exception $e) {
            Log::error('Failed to get campaign analytics by date range:', [
                'error' => $e->getMessage(),
                'campaign_id' => $campaign->id
            ]);
            throw $e;
        }
    }

    /**
     * Get marketer network statistics with campaign data
     * @return array
     */
    public function getEnhancedNetworkStats(MarketerUser $marketer): array
    {
        $stats = $this->getMarketerNetworkStats($marketer);

        $campaignStats = [
            'total_campaigns' => $marketer->campaigns()->count(),
            'active_campaigns' => $marketer->campaigns()->where('status', 'active')->count(),
            'network_campaigns' => $this->getAllChildCampaigns()->count(),
            'top_performing_campaigns' => $this->getTopPerformingCampaigns($marketer)
        ];

        return array_merge($stats, ['campaign_statistics' => $campaignStats]);
    }

    private function validateCampaignData(array $data): void
    {
        $required = ['name', 'commission_rate', 'start_date'];
        $missing = array_diff($required, array_keys($data));

        if (!empty($missing)) {
            throw new Exception('Missing required fields: ' . implode(', ', $missing));
        }

        if (isset($data['commission_rate']) && ($data['commission_rate'] < 0 || $data['commission_rate'] > 100)) {
            throw new Exception('Commission rate must be between 0 and 100');
        }

        if (isset($data['end_date']) && Carbon::parse($data['end_date'])->lt(Carbon::parse($data['start_date']))) {
            throw new Exception('End date must be after start date');
        }
    }

    /**
     * Aggregate traffic sources from analytics
     */
    private function aggregateTrafficSources(Collection $analytics): array
    {
        $sources = [];
        foreach ($analytics as $analytic) {
            if (!empty($analytic->traffic_sources)) {
                foreach ($analytic->traffic_sources as $source => $count) {
                    $sources[$source] = ($sources[$source] ?? 0) + $count;
                }
            }
        }
        return $sources;
    }

    /**
     * Aggregate device breakdown from analytics
     */
    private function aggregateDeviceBreakdown(Collection $analytics): array
    {
        $devices = [];
        foreach ($analytics as $analytic) {
            if (!empty($analytic->device_breakdown)) {
                foreach ($analytic->device_breakdown as $device => $count) {
                    $devices[$device] = ($devices[$device] ?? 0) + $count;
                }
            }
        }
        return $devices;
    }

    private function getTopPerformingCampaigns(MarketerUser $marketer): Collection
    {
        return Campaign::whereIn('marketer_id', function ($query) use ($marketer) {
            $query->select('id')
                ->from('marketer_users')
                ->where('parent_marketer_id', $marketer->id)
                ->orWhere('id', $marketer->id);
        })
            ->withCount('transactions')
            ->withSum('transactions', 'amount')
            ->orderByDesc('transactions_sum_amount')
            ->limit(5)
            ->get();
    }

    private function getDistributionsByLevel(Collection $transactions): array
    {
        return $transactions->flatMap->commissionDistributions
            ->groupBy('level')
            ->map(fn($distributions) => [
                'total_amount' => $distributions->sum('amount'),
                'count' => $distributions->count(),
                'average' => $distributions->average('amount')
            ])
            ->toArray();
    }

    private function getPerformanceByDate(Campaign $campaign): array
    {
        return $campaign->analytics()
            ->orderBy('date')
            ->get()
            ->map(function ($day) {
                return [
                    'date' => $day->date,
                    'visits' => $day->daily_visits,
                    'orders' => $day->approved_orders,
                    'commission' => $day->daily_commission,
                    'conversion_rate' => $day->conversion_rate
                ];
            })
            ->toArray();
    }

    /**
     * Get marketer network structure
     */
    public function getMarketerNetwork(MarketerUser $marketer): array
    {
        try {
            $network = [];
            $this->buildNetworkTree($marketer, $network);
            return $network;
        } catch (Exception $e) {
            Log::error('Failed to get marketer network:', [
                'error' => $e->getMessage(),
                'marketer_id' => $marketer->id
            ]);
            throw $e;
        }
    }

    /**
     * Build network tree recursively with eager loading
     * @param MarketerUser $marketer
     * @param array &$network
     * @return void
     */
    private function buildNetworkTree(MarketerUser $marketer, array &$network): void
    {
        // Optimize by eager loading relationships
        $children = MarketerUser::with(['user', 'campaigns' => function ($query) {
            $query->where('status', 'active');
        }])
            ->where('parent_marketer_id', $marketer->id)
            ->get();

        $network[] = [
            'id' => $marketer->id,
            'name' => $marketer->user->name,
            'level' => $marketer->level,
            'total_earned' => $marketer->total_earned,
            'active_campaigns' => $marketer->campaigns->count(),
            'children' => []
        ];

        $currentIndex = count($network) - 1;

        foreach ($children as $child) {
            $this->buildNetworkTree($child, $network[$currentIndex]['children']);
        }
    }

    /**
     * Calculate last 30 days earnings for a marketer
     * @param MarketerUser $marketer
     * @return float
     */
    private function calculateLast30DaysEarnings(MarketerUser $marketer): float
    {
        // Optimize by using a single query with proper indexing
        return CommissionDistribution::where('parent_marketer_id', $marketer->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->where('status', 'approved')
            ->select(DB::raw('COALESCE(SUM(amount), 0) as total'))
            ->value('total');
    }

    /**
     * Update marketer earnings summary
     */
    private function updateMarketerEarnings(MarketerUser $marketer, float $amount): void
    {
        $marketer->update([
            'total_earned' => $marketer->total_earned + $amount,
            'last_30_days_earnings' => $this->calculateLast30DaysEarnings($marketer)
        ]);

        $marketer->wallet()->increment('balance', $amount);
    }



    /**
     * Generate unique referral code
     */
    private function generateUniqueReferralCode(int $length = 8): string
    {
        do {
            $referralCode = strtoupper(substr(md5(uniqid()), 0, $length));
        } while (MarketerUser::where('referral_code', $referralCode)->exists());

        return $referralCode;
    }

    /**
     * Generate unique tracking code for campaign
     */
    private function generateUniqueTrackingCode(int $length = 10): string
    {
        do {
            $code = 'TRK_' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length));
        } while (Campaign::where('tracking_code', $code)->exists());

        return $code;
    }

    /**
     * Get marketer network statistics
     */
    public function getMarketerNetworkStats(MarketerUser $marketer): array
    {
        $childMarketers = $marketer->childMarketers;
        $secondLevelMarketers = collect();
        $thirdLevelMarketers = collect();

        foreach ($childMarketers as $child) {
            $secondLevelMarketers = $secondLevelMarketers->concat($child->childMarketers);
            foreach ($child->childMarketers as $secondLevel) {
                $thirdLevelMarketers = $thirdLevelMarketers->concat($secondLevel->childMarketers);
            }
        }

        return [
            'direct_marketers' => [
                'count' => $childMarketers->count(),
                'active_count' => $childMarketers->where('status', 'active')->count(),
                'total_earnings' => $childMarketers->sum('total_earned')
            ],
            'second_level_marketers' => [
                'count' => $secondLevelMarketers->count(),
                'active_count' => $secondLevelMarketers->where('status', 'active')->count(),
                'total_earnings' => $secondLevelMarketers->sum('total_earned')
            ],
            'third_level_marketers' => [
                'count' => $thirdLevelMarketers->count(),
                'active_count' => $thirdLevelMarketers->where('status', 'active')->count(),
                'total_earnings' => $thirdLevelMarketers->sum('total_earned')
            ]
        ];
    }

    /**
     * Get child marketers with their campaigns
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childMarketersWithCampaigns()
    {
        return $this->childMarketers()->with('campaigns');
    }

    /**
     * Get all campaigns associated with the marketer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'marketer_id');
    }

    /**
     * Get all campaigns from child marketers
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllChildCampaigns()
    {
        return $this->childMarketers()
            ->with('campaigns')
            ->get()
            ->pluck('campaigns')
            ->flatten();
    }

    /**
     * Create a new marketing link
     * @param MarketerUser $marketer
     * @param array $data
     * @return MarketerLink
     * @throws Exception
     */
    public function createMarketingLink(MarketerUser $marketer, array $data): MarketerLink
    {
        try {
            return DB::transaction(function () use ($marketer, $data) {
                $this->validateLinkData($data);

                $trackingCode = $this->generateUniqueLinkTrackingCode();
                $shortenedUrl = $this->generateShortenedUrl($data['original_url'], $trackingCode);

                return MarketerLink::create([
                    'marketer_id' => $marketer->id,
                    'campaign_id' => $data['campaign_id'] ?? null,
                    'product_id' => $data['product_id'] ?? null,
                    'provider_id' => $data['provider_id'] ?? null,
                    'original_url' => $data['original_url'],
                    'tracking_code' => $trackingCode,
                    'shortened_url' => $shortenedUrl,
                    'utm_source' => $data['utm_source'] ?? null,
                    'utm_medium' => $data['utm_medium'] ?? null,
                    'utm_campaign' => $data['utm_campaign'] ?? null,
                    'utm_content' => $data['utm_content'] ?? null,
                    'status' => $data['status'] ?? 'active',
                    'expires_at' => $data['expires_at'] ?? null
                ]);
            });
        } catch (Exception $e) {
            Log::error('Failed to create marketing link:', [
                'error' => $e->getMessage(),
                'marketer_id' => $marketer->id
            ]);
            throw $e;
        }
    }

    /**
     * Record a link click
     * @param MarketerLink $link
     * @param array $clickData
     * @return void
     */
    public function recordLinkClick(MarketerLink $link, array $clickData): void
    {
        try {
            DB::transaction(function () use ($link, $clickData) {
                // Check for unique visitor
                $uniqueIdentifier = $clickData['visitor_id'] ?? $clickData['ip'];
                $uniqueKey = "link:{$link->id}:visitors:" . now()->toDateString();

                $isUnique = !Redis::sismember($uniqueKey, $uniqueIdentifier);
                if ($isUnique) {
                    Redis::sadd($uniqueKey, $uniqueIdentifier);
                    Redis::expire($uniqueKey, 86400); // Expire after 24 hours
                    $link->increment('unique_clicks');
                }

                // Record click
                $link->increment('clicks');

                ClickTracking::create([
                    'marketer_link_id' => $link->id,
                    'ip_address' => $clickData['ip'] ?? null,
                    'user_agent' => $clickData['user_agent'] ?? null,
                    'referrer' => $clickData['referrer'] ?? null,
                    'device_type' => $clickData['device_type'] ?? null,
                    'country' => $clickData['country'] ?? null,
                    'city' => $clickData['city'] ?? null,
                    'is_unique' => $isUnique
                ]);

                // Update conversion rate
                if ($link->conversions > 0) {
                    $link->update([
                        'conversion_rate' => ($link->conversions / $link->clicks) * 100
                    ]);
                }
            });
        } catch (Exception $e) {
            Log::error('Failed to record link click:', [
                'error' => $e->getMessage(),
                'link_id' => $link->id
            ]);
            throw $e;
        }
    }

    /**
     * Record a link conversion
     * @param MarketerLink $link
     * @param array $conversionData
     * @return void
     */
    public function recordLinkConversion(MarketerLink $link, array $conversionData): void
    {
        try {
            DB::transaction(function () use ($link, $conversionData) {
                $link->increment('conversions');
                $link->increment('total_revenue', $conversionData['amount']);

                // Calculate commission
                $commissionRate = $link->campaign?->commission_rate ??
                    $this->getDefaultCommissionRate($link->marketer);
                $commission = $conversionData['amount'] * ($commissionRate / 100);

                $link->increment('total_commission', $commission);

                ConversionTracking::create([
                    'marketer_link_id' => $link->id,
                    'order_id' => $conversionData['order_id'],
                    'amount' => $conversionData['amount'],
                    'commission' => $commission,
                    'customer_id' => $conversionData['customer_id'] ?? null,
                    'product_id' => $conversionData['product_id'] ?? null
                ]);

                // Update conversion rate
                $link->update([
                    'conversion_rate' => ($link->conversions / $link->clicks) * 100
                ]);

                // Record transaction if campaign exists
                if ($link->campaign_id) {
                    $this->recordTransaction(
                        $link->marketer,
                        $conversionData['amount'],
                        $conversionData['order_id'],
                        $link->campaign
                    );
                }
            });
        } catch (Exception $e) {
            Log::error('Failed to record link conversion:', [
                'error' => $e->getMessage(),
                'link_id' => $link->id
            ]);
            throw $e;
        }
    }

    /**
     * Get link analytics
     * @param MarketerLink $link
     * @return array
     */
    public function getLinkAnalytics(MarketerLink $link): array
    {
        $clickStats = ClickTracking::where('marketer_link_id', $link->id)
            ->select(
                DB::raw('COUNT(*) as total_clicks'),
                DB::raw('COUNT(DISTINCT ip_address) as unique_visitors'),
                DB::raw('COUNT(DISTINCT DATE(created_at)) as active_days'),
                DB::raw('AVG(CASE WHEN is_unique THEN 1 ELSE 0 END) * 100 as unique_click_rate')
            )
            ->first();

        $deviceBreakdown = ClickTracking::where('marketer_link_id', $link->id)
            ->select('device_type', DB::raw('COUNT(*) as count'))
            ->groupBy('device_type')
            ->get()
            ->pluck('count', 'device_type')
            ->toArray();

        $locationStats = ClickTracking::where('marketer_link_id', $link->id)
            ->select('country', DB::raw('COUNT(*) as count'))
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->pluck('count', 'country')
            ->toArray();

        return [
            'general_stats' => [
                'total_clicks' => $clickStats->total_clicks,
                'unique_visitors' => $clickStats->unique_visitors,
                'active_days' => $clickStats->active_days,
                'unique_click_rate' => round($clickStats->unique_click_rate, 2),
                'conversions' => $link->conversions,
                'conversion_rate' => $link->conversion_rate,
                'total_revenue' => $link->total_revenue,
                'total_commission' => $link->total_commission
            ],
            'device_breakdown' => $deviceBreakdown,
            'top_countries' => $locationStats,
            'referrers' => $this->getTopReferrers($link),
            'hourly_distribution' => $this->getHourlyClickDistribution($link)
        ];
    }

    /**
     * Validate link data
     * @param array $data
     * @throws Exception
     */
    private function validateLinkData(array $data): void
    {
        if (empty($data['original_url'])) {
            throw new Exception('Original URL is required');
        }

        if (!filter_var($data['original_url'], FILTER_VALIDATE_URL)) {
            throw new Exception('Invalid URL format');
        }

        if (!empty($data['campaign_id'])) {
            $campaign = Campaign::find($data['campaign_id']);
            if (!$campaign) {
                throw new Exception('Invalid campaign ID');
            }
        }
    }

    /**
     * Generate unique tracking code for links
     */
    private function generateUniqueLinkTrackingCode(int $length = 10): string
    {
        do {
            $code = 'LNK_' . Str::random($length);
        } while (MarketerLink::where('tracking_code', $code)->exists());

        return $code;
    }

    /**
     * Generate shortened URL
     */
    private function generateShortenedUrl(string $originalUrl, string $trackingCode): string
    {
        // Implementation depends on your URL shortening service
        // This is a simple example using the tracking code
        return config('app.url') . '/go/' . $trackingCode;
    }

    /**
     * Get top referrers for a link
     */
    private function getTopReferrers(MarketerLink $link): array
    {
        return ClickTracking::where('marketer_link_id', $link->id)
            ->whereNotNull('referrer')
            ->select('referrer', DB::raw('COUNT(*) as count'))
            ->groupBy('referrer')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'referrer')
            ->toArray();
    }

    /**
     * Get hourly click distribution
     */
    private function getHourlyClickDistribution(MarketerLink $link): array
    {
        return ClickTracking::where('marketer_link_id', $link->id)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();
    }

    /**
     * Get default commission rate for marketer
     */
    private function getDefaultCommissionRate(MarketerUser $marketer): float
    {
        return MarketerTier::where('level', $marketer->level)
            ->value('percentage') ?? 10.0; // Default 10% if no tier found
    }
}
