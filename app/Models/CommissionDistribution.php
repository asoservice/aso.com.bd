<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionDistribution extends Model
{
    protected $fillable = [
        'transaction_id',
        'marketer_id',
        'parent_marketer_id',
        'level',
        'percentage',
        'amount',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(MarketerTransaction::class);
    }

    public function marketer(): BelongsTo
    {
        return $this->belongsTo(MarketerUser::class, 'marketer_id');
    }

    public function parentMarketer(): BelongsTo
    {
        return $this->belongsTo(MarketerUser::class, 'parent_marketer_id');
    }
}
