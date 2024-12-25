<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'rate',
        'status',
        'created_by_id',
    ];

    protected $casts = [
        'rate' => 'integer',
        'status' => 'integer',
        'created_by_id' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by_id = auth()?->user()?->id;
        });
    }

    public function product(): HasMany
    {
        return $this->hasMany(Service::class, 'tax_id');
    }
}
