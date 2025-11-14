<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'error' => 'Authentication required'
            ], 401);
        }

        // Check if post exists and is published
        if (!$post || $post->status !== 'published') {
            return response()->json([
                'error' => 'Post not found'
            ], 404);
        }

        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $post->likes()->create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
            ]);
            $liked = true;
        }

        // Update likes count
        $post->update([
            'likes_count' => $post->likes()->count()
        ]);

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->fresh()->likes_count
        ]);
    }

    public function bookmark(Request $request, Post $post)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'error' => 'Authentication required'
            ], 401);
        }

        // For now, we'll just return success
        // You can implement actual bookmark functionality later
        return response()->json(['bookmarked' => true]);
    }
}