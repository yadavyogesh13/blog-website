<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription; // Make sure this import exists
use App\Mail\NewsletterVerificationMail;
use App\Mail\NewsletterWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'source' => 'sometimes|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;
        $source = $request->source ?? 'website';

        try {
            // Check if email already exists
            $existingSubscription = NewsletterSubscription::withTrashed()
                ->where('email', $email)
                ->first();

            if ($existingSubscription) {
                if ($existingSubscription->trashed()) {
                    // Restore soft deleted subscription
                    $existingSubscription->restore();
                    $existingSubscription->update([
                        'is_active' => true,
                        'token' => Str::random(32),
                        'subscription_source' => $source,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]);
                    $subscription = $existingSubscription;
                } elseif ($existingSubscription->is_verified && $existingSubscription->is_active) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This email is already subscribed to our newsletter.'
                    ], 409);
                } else {
                    // Update existing unverified subscription
                    $existingSubscription->update([
                        'token' => Str::random(32),
                        'subscription_source' => $source,
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]);
                    $subscription = $existingSubscription;
                }
            } else {
                // Create new subscription
                $subscription = NewsletterSubscription::create([
                    'email' => $email,
                    'subscription_source' => $source,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            }

            // Send verification email
            try {
                // Make sure we're passing the correct model instance
                Mail::to($subscription->email)->send(new NewsletterVerificationMail($subscription));
                
                Log::info('Newsletter verification email sent', [
                    'email' => $subscription->email,
                    'source' => $source
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Please check your email to verify your subscription.'
                ]);

            } catch (\Exception $mailException) {
                Log::error('Failed to send newsletter verification email', [
                    'email' => $subscription->email,
                    'error' => $mailException->getMessage()
                ]);

                // Still return success but log the error
                return response()->json([
                    'success' => true,
                    'message' => 'Subscription received! Please check your email for verification.'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Newsletter subscription failed', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Verify email subscription
     */
    public function verify($token)
    {
        try {
            $subscription = NewsletterSubscription::where('token', $token)
                ->where('is_verified', false)
                ->first();

            if (!$subscription) {
                return redirect()->route('home')
                    ->with('error', 'Invalid or expired verification link.');
            }

            // Mark as verified
            $subscription->markAsVerified();

            // Send welcome email
            try {
                Mail::to($subscription->email)->send(new NewsletterWelcomeMail($subscription));
            } catch (\Exception $e) {
                Log::error('Failed to send welcome email', [
                    'email' => $subscription->email,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('Newsletter subscription verified', ['email' => $subscription->email]);

            return redirect()->route('home')
                ->with('success', 'Thank you for verifying your email! You are now subscribed to our newsletter.');

        } catch (\Exception $e) {
            Log::error('Newsletter verification failed', [
                'token' => $token,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('home')
                ->with('error', 'Verification failed. Please try again.');
        }
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe($token)
    {
        try {
            $subscription = NewsletterSubscription::where('token', $token)
                ->active()
                ->first();

            if (!$subscription) {
                return redirect()->route('home')
                    ->with('error', 'Invalid or expired unsubscribe link.');
            }

            $subscription->unsubscribe();

            Log::info('Newsletter subscription cancelled', ['email' => $subscription->email]);

            return redirect()->route('home')
                ->with('success', 'You have been unsubscribed from our newsletter.');

        } catch (\Exception $e) {
            Log::error('Newsletter unsubscribe failed', [
                'token' => $token,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('home')
                ->with('error', 'Unsubscribe failed. Please try again.');
        }
    }

    /**
     * Get subscription stats (for admin)
     */
    public function stats()
    {
        $totalSubscribers = NewsletterSubscription::verified()->active()->count();
        $pendingVerification = NewsletterSubscription::where('is_verified', false)->count();
        $todaySubscriptions = NewsletterSubscription::whereDate('created_at', today())->count();

        return response()->json([
            'total_subscribers' => $totalSubscribers,
            'pending_verification' => $pendingVerification,
            'today_subscriptions' => $todaySubscriptions
        ]);
    }
}