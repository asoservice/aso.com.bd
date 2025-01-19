<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'marketer_id',
        'name',
        'description',
        'target_amount',
        'commission_rate',
        'start_date',
        'end_date',
        'visits',
        'approved_orders',
        'total_commission',
        'conversion_rate',
        'tracking_code',
        'landing_page_url',
        'target_demographics',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'target_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'conversion_rate' => 'decimal:2',
        'target_demographics' => 'json'
    ];

    public function marketer(): BelongsTo
    {
        return $this->belongsTo(MarketerUser::class, 'marketer_id');
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(CampaignAnalytics::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(MarketerTransaction::class);
    }

    public function calculateConversionRate(): float
    {
        return $this->visits > 0 
            ? ($this->approved_orders / $this->visits) * 100 
            : 0;
    }

    public function updateAnalytics(): void
    {
        $this->update([
            'conversion_rate' => $this->calculateConversionRate(),
            'total_commission' => $this->transactions()->sum('commission_earned')
        ]);
    }
}
