<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClickTracking extends Model
{
    protected $casts = [
        'is_unique' => 'boolean',
        'metadata' => 'array'
    ];

    /**
     * Get the marketing link associated with the click
     */
    public function marketerLink(): BelongsTo
    {
        return $this->belongsTo(MarketerLink::class);
    }

    /**
     * Scope a query to only include unique clicks
     */
    public function scopeUnique($query)
    {
        return $query->where('is_unique', true);
    }

    /**
     * Scope a query to filter by date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get browser name from user agent
     */
    public function getBrowser(): ?string
    {
        return $this->parseUserAgent()['browser'] ?? null;
    }

    /**
     * Get operating system from user agent
     */
    public function getOS(): ?string
    {
        return $this->parseUserAgent()['os'] ?? null;
    }

    /**
     * Parse user agent string
     */
    private function parseUserAgent(): array
    {
        // Basic user agent parsing - you might want to use a proper library
        $userAgent = $this->user_agent;
        $browser = null;
        $os = null;

        // Detect browser
        if (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        }

        // Detect OS
        if (strpos($userAgent, 'Windows') !== false) {
            $os = 'Windows';
        } elseif (strpos($userAgent, 'Mac') !== false) {
            $os = 'MacOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            $os = 'Linux';
        } elseif (strpos($userAgent, 'iPhone') !== false) {
            $os = 'iOS';
        } elseif (strpos($userAgent, 'Android') !== false) {
            $os = 'Android';
        }

        return [
            'browser' => $browser,
            'os' => $os
        ];
    }
}
