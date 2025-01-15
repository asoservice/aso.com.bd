<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketerTransaction extends Model
{
    protected $fillable = [
        'marketer_wallet_id',
        'marketer_id',
        'amount',
        'type',
        'detail'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_earned' => 'decimal:2',
    ];

    public function marketer(): BelongsTo
    {
        return $this->belongsTo(MarketerUser::class, 'marketer_id');
    }

    public function commissionDistributions(): HasMany
    {
        return $this->hasMany(CommissionDistribution::class, 'transaction_id');
    }
}
