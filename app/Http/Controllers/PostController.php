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

        // Sort
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'popular':
                    $query->orderBy('views', 'desc');
                    break;
                case 'featured':
                    $query->featured()->orderBy('published_at', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $posts = $query->paginate(12);
        $categories = Category::where('is_active', true)->withCount('posts')->get();

        // SEO Data
        $seo = $this->generateIndexSeo($request, $posts);

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

        // Get related posts (same category)
        $related_posts = Post::with(['user', 'category'])
            ->published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get();

        // Get recent posts (all categories)
        $recent_posts = Post::with(['user', 'category'])
            ->published()
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(5)
            ->get();

        // Get categories with post counts
        $categories = Category::where('is_active', true)
            ->withCount(['posts' => function($query) {
                $query->published();
            }])
            ->having('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        return view('posts.show', compact('post', 'related_posts', 'recent_posts', 'categories'));
    }

    /**
     * Generate SEO data for posts index page
     */
    private function generateIndexSeo(Request $request, $posts)
    {
        $baseTitle = 'All Articles - ' . config('app.name');
        $baseDescription = 'Browse all articles on various topics including technology, lifestyle, business and more.';
        
        $seo = [
            'title' => $baseTitle,
            'description' => $baseDescription,
            'keywords' => 'articles, blog posts, stories, writing, technology, lifestyle, business',
            'canonical' => url()->current(),
        ];

        // Category-specific SEO
        if ($request->has('category') && $request->category) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $seo['title'] = $category->name . ' Articles - ' . config('app.name');
                $seo['description'] = 'Browse all ' . $category->name . ' articles. ' . $category->description;
                $seo['keywords'] = $category->name . ', ' . $seo['keywords'];
            }
        }

        // Pagination SEO
        if ($posts->currentPage() > 1) {
            $seo['title'] .= ' - Page ' . $posts->currentPage();
            $seo['description'] .= ' Page ' . $posts->currentPage();
        }

        return $seo;
    }

    /**
     * Generate SEO-friendly slug
     */
    public function generateSlug($title)
    {
        $slug = Str::slug($title);
        
        // Check if slug exists
        $count = Post::where('slug', 'LIKE', $slug . '%')->count();
        
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        return $slug;
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