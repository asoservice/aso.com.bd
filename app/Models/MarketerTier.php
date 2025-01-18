<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketerTier extends Model
{
    protected $fillable = [
        'level',
        'percentage',
        'description',
        'is_active',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
    ];
}
