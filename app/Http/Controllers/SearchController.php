<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $category = $request->get('category');
        $sort = $request->get('sort', 'relevance');
        $results = [];
        $searchTime = 0;

        if ($query) {
            $startTime = microtime(true);
            
            $searchQuery = Post::published()
                ->with(['user', 'category'])
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', '%' . $query . '%')
                      ->orWhere('excerpt', 'like', '%' . $query . '%')
                      ->orWhere('content', 'like', '%' . $query . '%')
                      ->orWhereHas('category', function($q) use ($query) {
                          $q->where('name', 'like', '%' . $query . '%');
                      })
                      ->orWhereHas('user', function($q) use ($query) {
                          $q->where('name', 'like', '%' . $query . '%');
                      });
                });

            // Filter by category
            if ($category) {
                $searchQuery->whereHas('category', function($q) use ($category) {
                    $q->where('slug', $category);
                });
            }

            // Apply sorting
            switch ($sort) {
                case 'latest':
                    $searchQuery->latest();
                    break;
                case 'popular':
                    $searchQuery->orderBy('views', 'desc');
                    break;
                case 'oldest':
                    $searchQuery->oldest();
                    break;
                default: // relevance
                    $searchQuery->orderByRaw("
                        CASE 
                            WHEN title LIKE ? THEN 3
                            WHEN excerpt LIKE ? THEN 2
                            WHEN content LIKE ? THEN 1
                            ELSE 0
                        END DESC
                    ", ["%{$query}%", "%{$query}%", "%{$query}%"]);
                    break;
            }

            $results = $searchQuery->paginate(12)
                ->appends(['q' => $query, 'category' => $category, 'sort' => $sort]);

            $searchTime = round((microtime(true) - $startTime) * 1000, 2); // Convert to milliseconds
        }

        $categories = Category::where('is_active', true)
            ->withCount(['posts' => function($query) {
                $query->published();
            }])
            ->orderBy('posts_count', 'desc')
            ->get();

        $popularSearches = $this->getPopularSearches();
        $relatedSearches = $query ? $this->getRelatedSearches($query) : [];

        $seo = $this->generateSearchSeo($query, $results);

        return view('search.index', compact(
            'results', 
            'query', 
            'categories', 
            'seo', 
            'searchTime',
            'popularSearches',
            'relatedSearches',
            'category',
            'sort'
        ));
    }

    /**
     * Generate SEO data for search page
     */
    private function generateSearchSeo($query, $results)
    {
        $baseTitle = 'Search - ' . config('app.name');
        $baseDescription = 'Search through our collection of articles, blog posts, and content.';

        if ($query) {
            $title = "Search Results for \"{$query}\" - " . config('app.name');
            $description = "Found {$results->total()} results for \"{$query}\". " . $baseDescription;
        } else {
            $title = $baseTitle;
            $description = $baseDescription;
        }

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => 'search, find articles, blog search, content search',
            'canonical' => $query ? route('search', ['q' => $query]) : route('search'),
            'og_image' => asset('images/search-og.jpg'),
            'twitter_image' => asset('images/search-twitter.jpg'),
            'schema' => $this->generateSearchSchema($query, $results),
        ];
    }

    /**
     * Generate Schema.org for search results
     */
    private function generateSearchSchema($query, $results)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'SearchResultsPage',
            'url' => route('search'),
            'name' => 'Search - ' . config('app.name'),
            'description' => 'Search through our collection of articles and blog posts',
            'isPartOf' => [
                '@type' => 'WebSite',
                'url' => url('/'),
                'name' => config('app.name')
            ]
        ];

        if ($query) {
            $schema['mainEntity'] = [
                '@type' => 'ItemList',
                'numberOfItems' => $results->total(),
                'itemListElement' => []
            ];
        }

        return $schema;
    }

    /**
     * Get popular searches (you can replace this with actual analytics data)
     */
    private function getPopularSearches()
    {
        return [
            'Laravel Tutorial',
            'JavaScript Tips',
            'Web Development',
            'CSS Framework',
            'PHP Programming',
            'React Guide',
            'Vue.js Tutorial',
            'Database Design',
            'API Development',
            'Mobile Responsive'
        ];
    }

    /**
     * Get related searches based on current query
     */
    private function getRelatedSearches($query)
    {
        $related = [
            'Laravel' => ['Laravel Tutorial', 'Laravel Framework', 'Laravel Eloquent', 'Laravel Authentication'],
            'JavaScript' => ['JavaScript Tutorial', 'ES6 Features', 'JavaScript Framework', 'Node.js'],
            'PHP' => ['PHP Tutorial', 'PHP Framework', 'PHP 8 Features', 'Composer'],
            'React' => ['React Tutorial', 'React Hooks', 'React Native', 'Next.js'],
            'Vue' => ['Vue.js Tutorial', 'Vue 3', 'Vuex', 'Nuxt.js'],
            'CSS' => ['CSS Framework', 'Tailwind CSS', 'CSS Grid', 'Flexbox'],
            'Database' => ['MySQL', 'PostgreSQL', 'Database Design', 'Eloquent ORM'],
        ];

        foreach ($related as $key => $terms) {
            if (stripos($query, $key) !== false) {
                return $terms;
            }
        }

        // Default related searches
        return [
            'Web Development Tutorial',
            'Programming Tips',
            'Coding Best Practices',
            'Software Development'
        ];
    }

    /**
     * Helper method to highlight search terms in text
     */
    public function highlightText($text, $query)
    {
        if (!$query || !$text) {
            return $text;
        }

        $words = array_filter(explode(' ', $query), function($word) {
            return strlen(trim($word)) > 2; // Only highlight words longer than 2 characters
        });

        if (empty($words)) {
            return $text;
        }

        $highlighted = $text;

        foreach ($words as $word) {
            $word = trim($word);
            $pattern = '/\b(' . preg_quote($word, '/') . ')\b/i';
            $replacement = '<mark class="bg-yellow-200 dark:bg-yellow-600 text-gray-900 dark:text-white px-1 rounded">$1</mark>';
            $highlighted = preg_replace($pattern, $replacement, $highlighted);
        }

        return $highlighted;
    }

    /**
     * Extract relevant snippet with highlighted terms
     */
    public function extractSnippet($content, $query, $length = 200)
    {
        if (!$query || !$content) {
            return Str::limit(strip_tags($content), $length);
        }

        $cleanContent = strip_tags($content);
        $query = strtolower($query);
        $contentLower = strtolower($cleanContent);

        // Find position of first occurrence of any query word
        $words = explode(' ', $query);
        $positions = [];

        foreach ($words as $word) {
            if (strlen($word) > 2) {
                $pos = strpos($contentLower, $word);
                if ($pos !== false) {
                    $positions[] = $pos;
                }
            }
        }

        if (empty($positions)) {
            return Str::limit($cleanContent, $length);
        }

        $startPos = min($positions);
        $start = max(0, $startPos - 50);
        $snippet = substr($cleanContent, $start, $length);

        // Ensure we don't cut words in the middle
        if ($start > 0) {
            $snippet = '...' . ltrim($snippet);
        }

        if (strlen($cleanContent) > $start + $length) {
            $snippet = rtrim($snippet) . '...';
        }

        return $this->highlightText($snippet, $query);
    }

    /**
     * AJAX search for real-time suggestions - FIXED VERSION
     */
    public function suggest(Request $request)
    {
        $query = $request->get('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        // Get post suggestions
        $postSuggestions = Post::published()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('excerpt', 'like', '%' . $query . '%');
            })
            ->select('title', 'slug')
            ->limit(5)
            ->get()
            ->map(function($post) {
                return [
                    'title' => $post->title,
                    'url' => route('posts.show', $post->slug),
                    'type' => 'article'
                ];
            })
            ->toArray();

        // Get category suggestions
        $categorySuggestions = Category::where('is_active', true)
            ->where('name', 'like', '%' . $query . '%')
            ->select('name', 'slug')
            ->limit(3)
            ->get()
            ->map(function($category) {
                return [
                    'title' => $category->name,
                    'url' => route('categories.show', $category->slug),
                    'type' => 'category'
                ];
            })
            ->toArray();

        // Merge arrays instead of collections
        $results = array_merge($postSuggestions, $categorySuggestions);
        
        // Limit to 8 results total
        $results = array_slice($results, 0, 8);

        return response()->json($results);
    }
}