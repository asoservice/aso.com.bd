<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransactions extends Model
{
    use HasFactory;

    protected $table = 'payment_gateways_transactions';

    protected $fillable = [
        'item_id',
        'amount',
        'transaction_id',
        'payment_method',
        'payment_status',
        'type',
        'request_type',
    ];

    protected $casts = [
        'item_id' => 'integer',
        'amount' => 'float',
    ];
}
