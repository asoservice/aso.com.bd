<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversionTracking extends Model
{
    protected $casts = [
        'amount' => 'decimal:2',
        'commission' => 'decimal:2',
        'metadata' => 'array'
    ];

    /**
     * Get the marketing link associated with the conversion
     */
    public function marketerLink(): BelongsTo
    {
        return $this->belongsTo(MarketerLink::class);
    }

    /**
     * Get the product associated with the conversion
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the customer associated with the conversion
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Scope a query to filter by date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
