<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'amount',
        'message',
        'status',
        'provider_wallet_id',
        'is_used',
        'payment_type',
        'provider_id',
        'admin_message',
    ];

    protected $casts = [
        'amount' => 'float',
        'message' => 'string',
        'admin_message' => 'string',
        'provider_wallet_id' => 'integer',
        'provider_id' => 'integer',
    ];

    protected $with = [
        'user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
