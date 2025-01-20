<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketerLink extends Model
{
    protected $casts = [
        'clicks' => 'integer',
        'unique_clicks' => 'integer',
        'conversions' => 'integer',
        'conversion_rate' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'expires_at' => 'datetime'
    ];

    protected $attributes = [
        'clicks' => 0,
        'unique_clicks' => 0,
        'conversions' => 0,
        'conversion_rate' => 0,
        'total_revenue' => 0,
        'total_commission' => 0,
        'status' => 'active'
    ];

    /**
     * Get the marketer that owns the link
     */
    public function marketer(): BelongsTo
    {
        return $this->belongsTo(MarketerUser::class, 'marketer_id');
    }

    /**
     * Get the campaign associated with the link
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the service associated with the link
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the provider associated with the link
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    /**
     * Get the click tracking records for the link
     */
    public function clicks(): HasMany
    {
        return $this->hasMany(ClickTracking::class);
    }

    /**
     * Get the conversion tracking records for the link
     */
    public function conversions(): HasMany
    {
        return $this->hasMany(ConversionTracking::class);
    }

    /**
     * Scope a query to only include active links
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
        ->where(function ($q) {
            $q->whereNull('expires_at')
            ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Check if the link is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get the link's full tracking URL
     */
    public function getTrackingUrl(): string
    {
        return $this->shortened_url ?? route('track.link', $this->tracking_code);
    }
}
