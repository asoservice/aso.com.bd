<?php

namespace App\Models;

use App\Enums\FrontEnum;
use App\Helpers\Helpers;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Blog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Sluggable, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'meta_title',
        'meta_description',
        'is_featured',
        'status',
        'created_by_id',
    ];

    public $with = [
        'media',
        'categories',
        'created_by',
        'tags',
    ];

    protected $casts = [
        'status' => 'integer',
        'is_featured' => 'integer',
        'created_by_id' => 'integer',
    ];

    public $withCount = [
        'comments',
    ];

    protected $appends = [
        'web_img_thumb_url',
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
            ],
        ];
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_categories', 'blog_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tags', 'blog_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getWebImgThumbUrlAttribute()
    {
       return Helpers::isFileExistsFromURL($this?->media?->last()?->getUrl(), true);
    }
}
