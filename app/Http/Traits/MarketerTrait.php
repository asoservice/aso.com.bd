<?php

namespace App\Traits;

use App\Models\{
    MarketerUser,
    MarketerTransaction,
    CommissionDistribution,
    MarketerTier,
    User
};
use Illuminate\Support\Facades\{DB, Log};
use Carbon\Carbon;
use Exception;

trait MarketerTrait
{
    private const MINIMUM_EARNING_REQUIREMENT = 100; // 100 taka
    private const MAX_MARKETER_LEVEL = 3;

    /**
     * Register a new marketer
     */
    public function registerMarketer(User $user, ?string $parentReferralCode = null): MarketerUser
    {
        try {
            return DB::transaction(function () use ($user, $parentReferralCode) {
                $parentMarketer = null;
                $level = 1;

                if ($parentReferralCode) {
                    $parentMarketer = MarketerUser::where('referral_code', $parentReferralCode)->first();
                    if ($parentMarketer) {
                        $level = $parentMarketer->level + 1;
                        if ($level > self::MAX_MARKETER_LEVEL) {
                            throw new Exception('Maximum marketer level reached');
                        }
                    }
                }

                $marketerUser = MarketerUser::create([
                    'user_id' => $user->id,
                    'referral_code' => $this->generateUniqueReferralCode(),
                    'parent_marketer_id' => $parentMarketer?->id,
                    'level' => $level,
                    'status' => 'active'
                ]);

                // Create initial earnings summary
                // MarketerEarningsSummary::create([
                //     'marketer_id' => $marketerUser->id,
                //     'last_calculated_at' => now()
                // ]);

                return $marketerUser;
            });
        } catch (Exception $e) {
            Log::error('Failed to register marketer: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Record a new transaction and distribute commissions
     */
    public function recordTransaction(
        MarketerUser $marketer,
        float $amount,
        string $orderId,
        string $type = 'sale'
    ): MarketerTransaction {
        try {
            return DB::transaction(function () use ($marketer, $amount, $orderId, $type) {
                // Create the transaction
                $transaction = MarketerTransaction::create([
                    'marketer_id' => $marketer->id,
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'commission_earned' => 0, // Will be updated after distribution
                    'status' => 'pending',
                    'transaction_type' => $type
                ]);

                // Distribute commissions
                $this->distributeCommissions($transaction);

                return $transaction->fresh();
            });
        } catch (Exception $e) {
            Log::error('Failed to record transaction: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Distribute commissions to parent marketers
     */
    private function distributeCommissions(MarketerTransaction $transaction): void
    {
        $marketer = $transaction->marketer;
        $currentParent = $marketer->parentMarketer;
        $level = 1;
        $totalCommission = 0;

        while ($currentParent && $level <= self::MAX_MARKETER_LEVEL) {
            // Check if parent meets minimum earning requirement
            $last30DaysEarnings = $this->calculateLast30DaysEarnings($currentParent);

            if ($last30DaysEarnings >= self::MINIMUM_EARNING_REQUIREMENT) {
                $MarketerTier = MarketerTier::where('level', $level)->first();
                $commissionAmount = $transaction->amount * ($MarketerTier->percentage / 100);

                // Create commission distribution record
                CommissionDistribution::create([
                    'transaction_id' => $transaction->id,
                    'marketer_id' => $marketer->id,
                    'parent_marketer_id' => $currentParent->id,
                    'level' => $level,
                    'percentage' => $MarketerTier->percentage,
                    'amount' => $commissionAmount,
                    'status' => 'pending'
                ]);

                $totalCommission += $commissionAmount;

                // Update parent's earnings
                $this->updateMarketerEarnings($currentParent, $commissionAmount);
            }

            $currentParent = $currentParent->parentMarketer;
            $level++;
        }

        // Update transaction with total commission
        $transaction->update([
            'commission_earned' => $totalCommission,
            'status' => 'approved'
        ]);
    }

    /**
     * Calculate marketer's earnings in the last 30 days
     */
    private function calculateLast30DaysEarnings(MarketerUser $marketer): float
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        return $marketer->transactions()
            ->where('status', 'approved')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('amount');
    }

    /**
     * Update marketer earnings summary
     */
    private function updateMarketerEarnings(MarketerUser $marketer, float $amount): void
    {
        $summary = $marketer->earningSummary;

        $summary->update([
            'total_earnings' => $summary->total_earnings + $amount,
            'available_balance' => $summary->available_balance + $amount,
            'last_calculated_at' => now()
        ]);

        // Update the last 30 days earnings
        $last30DaysEarnings = $this->calculateLast30DaysEarnings($marketer);
        $marketer->update(['last_30_days_earnings' => $last30DaysEarnings]);
    }

    /**
     * Process withdrawal request
     */
    public function processWithdrawal(
        MarketerUser $marketer,
        float $amount,
        string $paymentMethod,
        array $paymentDetails
    ): bool {
        try {
            return DB::transaction(function () use ($marketer, $amount, $paymentMethod, $paymentDetails) {
                $summary = $marketer->earningSummary;

                if ($summary->available_balance < $amount) {
                    throw new Exception('Insufficient balance');
                }

                // Create withdrawal record
                $withdrawal = $marketer->withdrawals()->create([
                    'amount' => $amount,
                    'status' => 'pending',
                    'payment_method' => $paymentMethod,
                    'payment_details' => $paymentDetails
                ]);

                // Update earnings summary
                $summary->update([
                    'available_balance' => $summary->available_balance - $amount,
                    'pending_balance' => $summary->pending_balance + $amount
                ]);

                return true;
            });
        } catch (Exception $e) {
            Log::error('Failed to process withdrawal: ' . $e->getMessage());
            throw $e;
        }
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
                'total_earnings' => $childMarketers->sum('total_earned_balance')
            ],
            'second_level_marketers' => [
                'count' => $secondLevelMarketers->count(),
                'active_count' => $secondLevelMarketers->where('status', 'active')->count(),
                'total_earnings' => $secondLevelMarketers->sum('total_earned_balance')
            ],
            'third_level_marketers' => [
                'count' => $thirdLevelMarketers->count(),
                'active_count' => $thirdLevelMarketers->where('status', 'active')->count(),
                'total_earnings' => $thirdLevelMarketers->sum('total_earned_balance')
            ]
        ];
    }

    /**
     * Update commission tiers
     */
    public function updateMarketerTiers(array $tiers): void
    {
        try {
            DB::transaction(function () use ($tiers) {
                foreach ($tiers as $level => $percentage) {
                    MarketerTier::updateOrCreate(
                        ['level' => $level],
                        ['percentage' => $percentage]
                    );
                }
            });
        } catch (Exception $e) {
            Log::error('Failed to update commission tiers: ' . $e->getMessage());
            throw $e;
        }
    }
}
