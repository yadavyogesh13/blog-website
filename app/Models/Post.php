<?php
// app/Models/Post.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category_id',
        'user_id',
        'status',
        'published_at',
        'views',
        'is_featured',
        'reading_time',
        'likes_count', 
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function analytics()
    {
        return $this->hasMany(Analytics::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeRecent(Builder $query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    // Accessors
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return ceil($wordCount / 200); // 200 words per minute
    }

    // SEO Helpers
    public function getMetaTitle()
    {
        return $this->seo->meta_title ?? $this->title;
    }

    public function getMetaDescription()
    {
        return $this->seo->meta_description ?? $this->excerpt;
    }

    public function getRouteKeyName()
    {
        // Use 'id' for admin routes, 'slug' for frontend routes
        if (request()->is('admin/*')) {
            return 'id';
        }
        return 'slug';
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // method to check if user liked the post
    public function isLikedByUser($userId = null)
    {
        if (!$userId) {
            $userId = auth()->id();
        }
        
        return $this->likes()->where('user_id', $userId)->exists();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if ($post->status === 'published' && !$post->published_at) {
                $post->published_at = now();
            }
        });
    }
}