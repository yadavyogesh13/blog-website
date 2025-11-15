<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories for frontend
     */
    public function index()
    {
        $categories = Category::withCount(['posts' => function($query) {
                $query->published();
            }])
            ->where('is_active', true)
            ->orderBy('posts_count', 'desc')
            ->get();

        $totalPosts = Post::published()->count();
        $popularCategories = Category::withCount(['posts' => function($query) {
                $query->published();
            }])
            ->where('is_active', true)
            ->orderBy('posts_count', 'desc')
            ->take(5)
            ->get();

        $seo = $this->generateIndexSeo();

        return view('categories.index', compact('categories', 'totalPosts', 'popularCategories', 'seo'));
    }

    /**
     * Display posts for a specific category
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $posts = $category->posts()
            ->published()
            ->with(['user', 'category'])
            ->latest()
            ->paginate(12);

        $relatedCategories = Category::where('is_active', true)
            ->where('id', '!=', $category->id)
            ->withCount(['posts' => function($query) {
                $query->published();
            }])
            ->orderBy('posts_count', 'desc')
            ->take(6)
            ->get();

        $seo = $this->generateCategorySeo($category, $posts);

        return view('categories.show', compact('category', 'posts', 'relatedCategories', 'seo'));
    }

    /**
     * Generate SEO data for categories index
     */
    private function generateIndexSeo()
    {
        return [
            'title' => 'Blog Categories - Browse Articles by Topic | ' . config('app.name'),
            'description' => 'Explore our comprehensive collection of blog categories. Find articles on technology, lifestyle, business, health, programming, and more. Organized content for easy discovery.',
            'keywords' => 'blog categories, topics, articles by category, technology, lifestyle, business, programming, web development',
            'canonical' => route('categories.index'),
            'og_image' => asset('images/categories-og.jpg'),
            'twitter_image' => asset('images/categories-twitter.jpg'),
            'schema' => $this->generateCategoriesSchema(),
        ];
    }

    /**
     * Generate SEO data for specific category
     */
    private function generateCategorySeo($category, $posts)
    {
        $title = $category->name . ' Articles - Latest ' . $category->name . ' Posts | ' . config('app.name');
        $description = $category->description ?? 'Browse our latest ' . $category->name . ' articles and blog posts. Stay updated with the newest content in ' . $category->name . '.';
        
        if ($posts->currentPage() > 1) {
            $title .= ' - Page ' . $posts->currentPage();
            $description .= ' Page ' . $posts->currentPage();
        }

        return [
            'title' => $title,
            'description' => Str::limit($description, 160),
            'keywords' => $category->name . ', ' . $category->name . ' articles, ' . $category->name . ' blog posts, latest ' . $category->name,
            'canonical' => route('categories.show', $category->slug),
            'og_image' => $category->image ? Storage::url($category->image) : asset('images/category-og.jpg'),
            'twitter_image' => $category->image ? Storage::url($category->image) : asset('images/category-twitter.jpg'),
            'schema' => $this->generateCategorySchema($category, $posts),
        ];
    }

    /**
     * Generate Schema.org for categories index
     */
    private function generateCategoriesSchema()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => 'Blog Categories',
            'description' => 'Explore articles organized by categories and topics',
            'url' => route('categories.index'),
            'mainEntity' => [
                '@type' => 'ItemList',
                'numberOfItems' => Category::where('is_active', true)->count(),
                'itemListElement' => []
            ]
        ];
    }

    /**
     * Generate Schema.org for specific category
     */
    private function generateCategorySchema($category, $posts)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $category->name . ' Articles',
            'description' => $category->description ?? 'Collection of ' . $category->name . ' articles',
            'url' => route('categories.show', $category->slug),
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => config('app.name'),
                'url' => url('/')
            ]
        ];
    }

    /**
     * API method to get categories
     */
    public function getCategories()
    {
        $categories = Category::where('is_active', true)
            ->withCount(['posts' => function($query) {
                $query->published();
            }])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
            'meta' => [
                'total' => $categories->count(),
                'total_posts' => Post::published()->count()
            ]
        ]);
    }
}