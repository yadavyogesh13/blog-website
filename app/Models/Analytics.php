<?php
// app/Models/Analytics.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'ip_address',
        'user_agent',
        'referrer',
        'country',
        'device_type',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}