<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

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

        $seo = [
            'title' => 'Categories - BlogSite',
            'description' => 'Browse articles by categories including technology, lifestyle, business, health and more.',
            'keywords' => 'categories, topics, articles',
            'canonical' => route('categories.index'),
        ];

        return view('categories.index', compact('categories', 'seo'));
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

        $seo = [
            'title' => $category->name . ' - BlogSite',
            'description' => $category->description ?? 'Browse ' . $category->name . ' articles on BlogSite.',
            'keywords' => $category->name . ', articles, blog posts',
            'canonical' => route('categories.show', $category->slug),
        ];

        return view('categories.show', compact('category', 'posts', 'seo'));
    }

    /**
     * API method to get categories (if needed)
     */
    public function getCategories()
    {
        $categories = Category::where('is_active', true)
            ->withCount('posts')
            ->get();

        return response()->json($categories);
    }
}