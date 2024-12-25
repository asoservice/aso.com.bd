<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeSlot extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'time_unit',
        'gap',
        'time_slots',
        'provider_id',
        'serviceman_id',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'time_slots' => 'json',
        'provider_id' => 'integer',
    ];

    protected $with = [
        'provider',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id', 'id');
    }
}
