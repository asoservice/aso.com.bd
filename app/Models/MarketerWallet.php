<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketerWallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'marketer_id',
        'balance',
    ];

    protected $casts = [
        'marketer_id' => 'integer',
        'balance' => 'float',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(MarketerTransaction::class, 'marketer_wallet_id')->orderBy('created_at', 'desc');
    }

    public function marketer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marketer_id');
    }
}
