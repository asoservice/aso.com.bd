<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketerUser extends Model
{
    protected $fillable = [
        'user_id',
        'referral_code',
        'parent_marketer_id',
        'level',
        'status',
        'total_earned_balance',
        'last_30_days_earnings',
    ];

    protected $casts = [
        'total_earned_balance' => 'decimal:2',
        'last_30_days_earnings' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parentAffiliate(): BelongsTo
    {
        return $this->belongsTo(MarketerUser::class, 'parent_marketer_id');
    }

    public function childAffiliates(): HasMany
    {
        return $this->hasMany(MarketerUser::class, 'parent_marketer_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(MarketerTransaction::class, 'affiliate_id');
    }

    public function commissionDistributions(): HasMany
    {
        return $this->hasMany(CommissionDistribution::class, 'affiliate_id');
    }

    public function parentCommissionDistributions(): HasMany
    {
        return $this->hasMany(CommissionDistribution::class, 'parent_marketer_id');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(MarketerWithdrawRequest::class, 'affiliate_id');
    }
}
