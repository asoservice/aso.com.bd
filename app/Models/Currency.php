<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Currency extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $fillable = [
        'code',
        'symbol',
        'no_of_decimal',
        'exchange_rate',
        'system_reserve',
        'status',
    ];

    protected $with = [
        'media',
    ];

    protected $casts = [
        'status' => 'integer',
        'exchange_rate' => 'float',
        'no_of_decimal' => 'integer',
        'system_reserve' =>  'integer'
    ];
}
