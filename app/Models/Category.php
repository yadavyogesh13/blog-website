<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function seo()
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function getRouteKeyName()
    {
        // Use 'id' for admin routes, 'slug' for frontend routes
        if (request()->is('admin/*')) {
            return 'id';
        }
        return 'slug';
    }

    public function getPostsCountAttribute()
    {
        return $this->posts()->published()->count();
    }
}