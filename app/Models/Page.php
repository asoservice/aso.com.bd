<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Page extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'metatitle',
        'metadescripation',
        'image',
        'status',
        'created_by_id',
        'meta_title',
        'meta_description',
    ];

    protected $with = [
        'media'
    ];

    protected $casts = [
        'created_by_id' => 'integer',
        'status' => 'integer',
    ];

    protected $hidden = [
        'metatitle',
        'metadescripation',
        'meta_title',
        'meta_description',
    ];
}
