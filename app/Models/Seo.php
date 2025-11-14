<?php
// app/Models/Seo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;

    protected $fillable = [
        'seoable_id',
        'seoable_type',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'structured_data',
        'canonical_url',
    ];

    protected $casts = [
        'structured_data' => 'array',
    ];

    public function seoable()
    {
        return $this->morphTo();
    }
}