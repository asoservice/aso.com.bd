<?php

namespace App\Http\Traits;

use Exception;
use App\Models\Campaign;
use App\Models\CampaignAnalytics;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

trait MarketerAnalyticsTrait
{

  /**
   * Record campaign visit
   */
  public function recordCampaignVisit(
    Campaign $campaign,
    array $visitorData = []
  ): void {
    try {
      DB::transaction(function () use ($campaign, $visitorData) {
        $today = now()->toDateString();

        $analytics = CampaignAnalytics::firstOrNew([
          'campaign_id' => $campaign->id,
          'date' => $today
        ]);

        // Update or initialize analytics
        $analytics->daily_visits = ($analytics->daily_visits ?? 0) + 1;

        // Track unique visitors using IP or visitor ID
        if (!empty($visitorData['visitor_id']) || !empty($visitorData['ip'])) {
          $uniqueIdentifier = $visitorData['visitor_id'] ?? $visitorData['ip'];
          $uniqueKey = "campaign:{$campaign->id}:visitors:{$today}";

          if (!Redis::sismember($uniqueKey, $uniqueIdentifier)) {
            Redis::sadd($uniqueKey, $uniqueIdentifier);
            Redis::expire($uniqueKey, 86400); // 24 hours
            $analytics->unique_visitors = ($analytics->unique_visitors ?? 0) + 1;
          }
        }

        // Update device breakdown
        if (!empty($visitorData['device_type'])) {
          $deviceBreakdown = $analytics->device_breakdown ?? [];
          $deviceBreakdown[$visitorData['device_type']] =
          ($deviceBreakdown[$visitorData['device_type']] ?? 0) + 1;
          $analytics->device_breakdown = $deviceBreakdown;
        }

        // Update traffic sources
        if (!empty($visitorData['source'])) {
          $trafficSources = $analytics->traffic_sources ?? [];
          $trafficSources[$visitorData['source']] =
          ($trafficSources[$visitorData['source']] ?? 0) + 1;
          $analytics->traffic_sources = $trafficSources;
        }

        // Calculate conversion rate
        if ($analytics->daily_visits > 0) {
          $analytics->conversion_rate =
            ($analytics->approved_orders / $analytics->daily_visits) * 100;
        }

        $analytics->save();

        // Update campaign totals
        $campaign->increment('visits');
        $campaign->updateAnalytics();
      });
    } catch (Exception $e) {
      Log::error('Failed to record campaign visit:', [
        'error' => $e->getMessage(),
        'campaign_id' => $campaign->id
      ]);
      throw $e;
    }
  }

  /**
   * Record order conversion
   */
  public function recordOrderConversion(
    Campaign $campaign,
    float $amount,
    string $orderId
  ): void {
    try {
      DB::transaction(function () use ($campaign, $amount, $orderId) {
        // Record transaction
        $transaction = $this->recordTransaction(
          $campaign->marketer,
          $amount,
          $orderId,
          $campaign
        );

        // Update daily analytics
        $analytics = CampaignAnalytics::firstOrNew([
          'campaign_id' => $campaign->id,
          'date' => now()->toDateString()
        ]);

        $analytics->approved_orders = ($analytics->approved_orders ?? 0) + 1;
        $analytics->daily_commission = ($analytics->daily_commission ?? 0) + $transaction->commission_earned;

        if ($analytics->daily_visits > 0) {
          $analytics->conversion_rate =
            ($analytics->approved_orders / $analytics->daily_visits) * 100;
        }

        $analytics->save();

        // Update campaign totals
        $campaign->increment('approved_orders');
        $campaign->updateAnalytics();
      });
    } catch (Exception $e) {
      Log::error('Failed to record order conversion:', [
        'error' => $e->getMessage(),
        'campaign_id' => $campaign->id,
        'order_id' => $orderId
      ]);
      throw $e;
    }
  }

  /**
   * Generate tracking code for campaign
   */
  private function generateUniqueTrackingCode(int $length = 10): string
  {
    do {
      $trackingCode = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length));
    } while (Campaign::where('tracking_code', $trackingCode)->exists());

    return $trackingCode;
  }

  /**
   * Get detailed campaign analytics by date range
   */
  public function getCampaignAnalyticsByDateRange(
    Campaign $campaign,
    string $startDate,
    string $endDate
  ): array {
    $analytics = $campaign->analytics()
      ->whereBetween('date', [$startDate, $endDate])
      ->get();

    return [
      'daily_stats' => $analytics->map(fn($day) => [
        'date' => $day->date->format('Y-m-d'),
        'visits' => $day->daily_visits,
        'unique_visitors' => $day->unique_visitors,
        'orders' => $day->approved_orders,
        'commission' => $day->daily_commission,
        'conversion_rate' => $day->conversion_rate,
        'bounce_rate' => $day->bounce_rate,
        'average_time_spent' => $day->average_time_spent,
      ])->toArray(),

      'totals' => [
        'visits' => $analytics->sum('daily_visits'),
        'unique_visitors' => $analytics->sum('unique_visitors'),
        'orders' => $analytics->sum('approved_orders'),
        'commission' => $analytics->sum('daily_commission'),
        'average_conversion' => $analytics->avg('conversion_rate'),
        'average_bounce_rate' => $analytics->avg('bounce_rate'),
        'average_time_spent' => $analytics->avg('average_time_spent'),
      ],

      'traffic_sources' => $this->aggregateTrafficSources($analytics),
      'device_breakdown' => $this->aggregateDeviceBreakdown($analytics)
    ];
  }

  private function aggregateTrafficSources(Collection $analytics): array
  {
    $sources = [];
    foreach ($analytics as $day) {
      foreach ($day->traffic_sources ?? [] as $source => $count) {
        $sources[$source] = ($sources[$source] ?? 0) + $count;
      }
    }
    return $sources;
  }

  private function aggregateDeviceBreakdown(Collection $analytics): array
  {
    $devices = [];
    foreach ($analytics as $day) {
      foreach ($day->device_breakdown ?? [] as $device => $count) {
        $devices[$device] = ($devices[$device] ?? 0) + $count;
      }
    }
    return $devices;
  }
}
