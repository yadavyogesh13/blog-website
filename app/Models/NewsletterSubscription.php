<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class NewsletterSubscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email',
        'token',
        'is_verified',
        'verified_at',
        'is_active',
        'subscription_source',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'verified_at' => 'datetime'
    ];

    /**
     * Boot function for model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->token = Str::random(32);
            $model->ip_address = request()->ip();
            $model->user_agent = request()->userAgent();
        });
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for verified subscriptions
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Mark subscription as verified
     */
    public function markAsVerified()
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
            'token' => null
        ]);
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe()
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Resubscribe to newsletter
     */
    public function resubscribe()
    {
        $this->update([
            'is_active' => true,
            'token' => Str::random(32)
        ]);
    }

    /**
     * Get verification URL
     */
    public function getVerificationUrl()
    {
        return route('newsletter.verify', ['token' => $this->token]);
    }

    /**
     * Get unsubscribe URL
     */
    public function getUnsubscribeUrl()
    {
        return route('newsletter.unsubscribe', ['token' => $this->token]);
    }
}