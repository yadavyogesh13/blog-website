<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Cache the data for better performance (1 hour)
        $data = Cache::remember('homepage_data', 3600, function () {
            return [
                'popular_posts' => Post::with(['category', 'user'])
                    ->published()
                    ->where('published_at', '>=', now()->subDays(30))
                    ->orderBy('views', 'desc')
                    ->take(6)
                    ->get(),
                
                'recent_posts' => Post::with(['category', 'user'])
                    ->published()
                    ->latest()
                    ->take(9)
                    ->get(),
                
                'categories' => Category::withCount(['posts' => function($query) {
                    $query->published();
                }])
                ->where('is_active', true)
                ->orderBy('posts_count', 'desc')
                ->get(),

                // Add some stats for the hero section
                'total_posts' => Post::published()->count(),
                'total_categories' => Category::where('is_active', true)->count(),
                'total_authors' => User::has('posts')->count(),
            ];
        });

        // SEO Meta Data
        $seo = [
            'title' => 'BlogSite - Stay Curious',
            'description' => 'Discover stories, thinking, and expertise from writers on any topic.',
            'keywords' => 'blog, articles, stories, writing, reading',
            'canonical' => url('/'),
        ];

        return view('home', array_merge($data, ['seo' => $seo]));
    }

    public function getFeaturedPosts()
    {
        $posts = Post::with(['category', 'user'])
            ->published()
            ->featured()
            ->latest()
            ->take(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function getRecentPosts()
    {
        $posts = Post::with(['category', 'user'])
            ->published()
            ->latest()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function getPopularPosts()
    {
        $posts = Post::with(['category', 'user'])
            ->published()
            ->where('published_at', '>=', now()->subDays(30))
            ->orderBy('views', 'desc')
            ->take(6)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }
}