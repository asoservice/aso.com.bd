<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'actions',
    ];

    protected $casts = [
        'actions' => 'array',
    ];
}
