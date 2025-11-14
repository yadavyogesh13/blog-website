<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Analytics;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'category'])->published();

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->latest()->paginate(12);
        $categories = Category::where('is_active', true)->withCount('posts')->get();

        $seo = [
            'title' => 'All Articles - BlogSite',
            'description' => 'Browse all articles on various topics including technology, lifestyle, business and more.',
            'keywords' => 'articles, blog posts, stories, writing',
            'canonical' => route('posts.index'),
        ];

        return view('posts.index', compact('posts', 'categories', 'seo'));
    }

    public function show($slug)
    {
        $post = Post::with(['user', 'category', 'seo'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views
        $post->increment('views');

        // Track analytics
        $this->trackView($post);

        // Get related posts
        $related_posts = Post::with(['user', 'category'])
            ->published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get();

        // SEO data from post's SEO or fallback
        $seo = [
            'title' => $post->getMetaTitle(),
            'description' => $post->getMetaDescription(),
            'keywords' => $post->seo->meta_keywords ?? '',
            'canonical' => route('posts.show', $post->slug),
        ];

        return view('posts.show', compact('post', 'related_posts', 'seo'));
    }

    public function getPosts(Request $request)
    {
        $posts = Post::published()
            ->with(['user', 'category'])
            ->latest()
            ->paginate($request->get('per_page', 12));

        return response()->json($posts);
    }

    private function trackView(Post $post)
    {
        try {
            Analytics::create([
                'post_id' => $post->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'referrer' => request()->header('referer'),
                'device_type' => $this->getDeviceType(),
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the page
            \Log::error('Analytics tracking failed: ' . $e->getMessage());
        }
    }

    private function getDeviceType()
    {
        $agent = request()->userAgent();
        if (strpos($agent, 'Mobile') !== false) {
            return 'mobile';
        } elseif (strpos($agent, 'Tablet') !== false) {
            return 'tablet';
        } else {
            return 'desktop';
        }
    }
}