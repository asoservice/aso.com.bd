<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ServicePackage extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Sluggable,SoftDeletes;

    protected $fillable = [
        'title',
        'price',
        'status',
        'is_featured',
        'discount',
        'slug',
        'description',
        'disclaimer',
        'hexa_code',
        'bg_color',
        'meta_title',
        'meta_description',
        'created_by_id',
        'provider_id',
        'started_at',
        'ended_at',
    ];

    public $with = [
        'services',
        'media',
        'user',
    ];

    protected $casts = [
        'price' => 'float',
        'status' => 'integer',
        'discount' => 'float',
        'provider_id' => 'integer',
        'is_featured' => 'integer',
        'created_by_id' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by_id = auth()->user()->id;
        });
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => true,
            ],
        ];
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_package_services');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'provider_id', 'id');
    }
}
