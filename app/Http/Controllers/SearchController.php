<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $results = [];

        if ($query) {
            $results = Post::published()
                ->with(['user', 'category'])
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', '%' . $query . '%')
                      ->orWhere('excerpt', 'like', '%' . $query . '%')
                      ->orWhere('content', 'like', '%' . $query . '%');
                })
                ->latest()
                ->paginate(12)
                ->appends(['q' => $query]);
        }

        $categories = Category::where('is_active', true)
            ->withCount('posts')
            ->get();

        $seo = [
            'title' => 'Search - BlogSite',
            'description' => 'Search for articles and blog posts on BlogSite.',
            'keywords' => 'search, find articles, blog search',
            'canonical' => route('search'),
        ];

        return view('search.index', compact('results', 'query', 'categories', 'seo'));
    }

    /**
     * Helper method to highlight search terms in text
     */
    public function highlightText($text, $query)
    {
        if (!$query || !$text) {
            return $text;
        }

        $words = explode(' ', $query);
        $highlighted = $text;

        foreach ($words as $word) {
            $word = trim($word);
            if (strlen($word) > 2) { // Only highlight words longer than 2 characters
                $pattern = '/\b(' . preg_quote($word, '/') . ')\b/i';
                $replacement = '<span class="bg-yellow-200 dark:bg-yellow-600 px-1 rounded">$1</span>';
                $highlighted = preg_replace($pattern, $replacement, $highlighted);
            }
        }

        return $highlighted;
    }
}