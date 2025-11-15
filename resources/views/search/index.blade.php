@extends('layouts.app')

@section('title', $seo['title'])

@section('meta')
    <!-- Primary Meta Tags -->
    <meta name="description" content="{{ $seo['description'] }}">
    <meta name="keywords" content="{{ $seo['keywords'] }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:image" content="{{ $seo['og_image'] }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ $seo['canonical'] }}">
    <meta property="twitter:title" content="{{ $seo['title'] }}">
    <meta property="twitter:description" content="{{ $seo['description'] }}">
    <meta property="twitter:image" content="{{ $seo['twitter_image'] }}">
    <meta property="twitter:creator" content="@yourtwitterhandle">
    
    <!-- Pagination SEO -->
    @if($query && $results->hasPages())
        @if($results->currentPage() > 1)
        <link rel="prev" href="{{ $results->previousPageUrl() }}">
        @endif
        @if($results->hasMorePages())
        <link rel="next" href="{{ $results->nextPageUrl() }}">
        @endif
    @endif
    
    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    @json($seo['schema'])
    </script>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Breadcrumb -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700" aria-label="Breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-3">
                <ol class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="{{ url('/') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" itemprop="item">
                            <span itemprop="name">Home</span>
                        </a>
                        <meta itemprop="position" content="1" />
                    </li>
                    <li class="flex items-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <span class="text-gray-900 dark:text-white font-medium" itemprop="name">Search</span>
                        <meta itemprop="position" content="2" />
                    </li>
                    @if($query)
                    <li class="flex items-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <span class="text-gray-900 dark:text-white font-medium" itemprop="name">"{{ $query }}"</span>
                        <meta itemprop="position" content="3" />
                    </li>
                    @endif
                </ol>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <main class="lg:w-2/3" role="main">
                <!-- Search Header -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        @if($query)
                            Search Results for "<span class="text-blue-600 dark:text-blue-400">"{{ $query }}"</span>"
                        @else
                            Search Articles
                        @endif
                    </h1>

                    <!-- Search Form -->
                    <form action="{{ route('search') }}" method="GET" class="space-y-4">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 relative">
                                <input 
                                    type="text" 
                                    name="q" 
                                    value="{{ $query }}"
                                    placeholder="Search articles, topics, categories, or authors..."
                                    class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                    required
                                    id="search-input"
                                    aria-label="Search query"
                                >
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <!-- Search Suggestions -->
                                <div id="search-suggestions" class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg hidden"></div>
                            </div>
                            <button 
                                type="submit"
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 flex items-center justify-center shadow-lg hover:shadow-xl"
                            >
                                <i class="fas fa-search mr-2"></i>
                                Search
                            </button>
                        </div>

                        <!-- Advanced Search Options -->
                        @if($query)
                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                            <!-- Category Filter -->
                            <div class="flex items-center space-x-2">
                                <label for="category-filter" class="text-sm font-medium text-gray-700 dark:text-gray-300">Category:</label>
                                <select name="category" id="category-filter" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->slug }}" {{ $category == $cat->slug ? 'selected' : '' }}>
                                        {{ $cat->name }} ({{ $cat->posts_count }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sort Options -->
                            <div class="flex items-center space-x-2">
                                <label for="sort-filter" class="text-sm font-medium text-gray-700 dark:text-gray-300">Sort by:</label>
                                <select name="sort" id="sort-filter" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                    <option value="relevance" {{ $sort == 'relevance' ? 'selected' : '' }}>Relevance</option>
                                    <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Latest</option>
                                    <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                    <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                </select>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>

                <!-- Search Results -->
                @if($query)
                    <!-- Search Stats -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <p class="text-blue-800 dark:text-blue-200 font-medium">
                                Found <span class="font-bold">{{ $results->total() }}</span> 
                                result{{ $results->total() === 1 ? '' : 's' }} for "<span class="font-semibold">{{ $query }}</span>"
                                @if($category)
                                    in <span class="font-semibold">{{ $categories->where('slug', $category)->first()->name ?? $category }}</span>
                                @endif
                            </p>
                            @if($searchTime)
                            <p class="text-blue-600 dark:text-blue-300 text-sm">
                                Search took <span class="font-mono">{{ $searchTime }}ms</span>
                            </p>
                            @endif
                        </div>
                    </div>

                    @if($results->count() > 0)
                        <!-- Results Grid -->
                        <div class="space-y-6 mb-8">
                            @foreach($results as $post)
                            <article class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700 group" 
                                     itemscope itemtype="https://schema.org/Article">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold px-2.5 py-1 rounded-full" itemprop="articleSection">
                                                {{ $post->category->name ?? 'Uncategorized' }}
                                            </span>
                                            @if($post->is_featured)
                                            <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-xs font-semibold px-2.5 py-1 rounded-full flex items-center">
                                                <i class="fas fa-star mr-1 text-xs"></i>
                                                Featured
                                            </span>
                                            @endif
                                        </div>
                                        <time datetime="{{ $post->published_at->toISOString() }}" class="text-sm text-gray-500 dark:text-gray-400" itemprop="datePublished">
                                            {{ $post->published_at->format('M d, Y') }}
                                        </time>
                                    </div>
                                    
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200" itemprop="headline">
                                        <a href="{{ route('posts.show', $post->slug) }}" class="hover:no-underline" itemprop="url">
                                            {!! app('App\Http\Controllers\SearchController')->highlightText($post->title, $query) !!}
                                        </a>
                                    </h2>
                                    
                                    <p class="text-gray-600 dark:text-gray-400 mb-4 leading-relaxed" itemprop="description">
                                        {!! app('App\Http\Controllers\SearchController')->extractSnippet($post->excerpt ?: $post->content, $query) !!}
                                    </p>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                                <img 
                                                    src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&background=random" 
                                                    alt="{{ $post->user->name }}"
                                                    class="w-6 h-6 rounded-full mr-2"
                                                    itemprop="image"
                                                >
                                                <span itemprop="name">{{ $post->user->name }}</span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="flex items-center">
                                                    <i class="fas fa-eye mr-1 text-xs"></i>
                                                    <span itemprop="interactionStatistic" itemscope itemtype="https://schema.org/InteractionCounter">
                                                        <span itemprop="userInteractionCount">{{ number_format($post->views) }}</span>
                                                    </span>
                                                </span>
                                                <span class="flex items-center">
                                                    <i class="fas fa-clock mr-1 text-xs"></i>
                                                    {{ $post->reading_time }} min read
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ route('posts.show', $post->slug) }}" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-semibold transition duration-200 flex items-center group/link">
                                            Read More
                                            <i class="fas fa-arrow-right ml-1 transform group-hover/link:translate-x-1 transition-transform duration-200"></i>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Hidden Microdata -->
                                <meta itemprop="dateModified" content="{{ $post->updated_at->toISOString() }}">
                                <meta itemprop="publisher" content="{{ config('app.name') }}">
                                <link itemprop="mainEntityOfPage" href="{{ route('posts.show', $post->slug) }}" />
                            </article>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($results->hasPages())
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                            {{ $results->onEachSide(1)->links() }}
                        </div>
                        @endif
                    @else
                        <!-- No Results -->
                        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                            <div class="text-gray-400 dark:text-gray-500 mb-4">
                                <i class="fas fa-search text-6xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">No results found</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                                We couldn't find any articles matching "<strong>{{ $query }}</strong>". Try different keywords or browse our categories.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                <a href="{{ route('posts.index') }}" 
                                   class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 inline-flex items-center justify-center">
                                    <i class="fas fa-newspaper mr-2"></i>
                                    Browse All Articles
                                </a>
                                <a href="{{ route('categories.index') }}" 
                                   class="border-2 border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400 px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 hover:text-white dark:hover:bg-blue-400 dark:hover:text-white transition duration-200 inline-flex items-center justify-center">
                                    <i class="fas fa-th-large mr-2"></i>
                                    Browse Categories
                                </a>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Empty Search State -->
                    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="text-gray-400 dark:text-gray-500 mb-4">
                            <i class="fas fa-search text-6xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">What are you looking for?</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">
                            Enter keywords to search through our articles, categories, and authors. You can search by title, content, category name, or author name.
                        </p>
                        
                        <!-- Popular Searches -->
                        @if($popularSearches)
                        <div class="max-w-md mx-auto">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Popular Searches</h4>
                            <div class="flex flex-wrap gap-2 justify-center">
                                @foreach($popularSearches as $popularSearch)
                                <a href="{{ route('search', ['q' => $popularSearch]) }}" 
                                   class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-lg text-sm hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                    {{ $popularSearch }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                @endif

                <!-- Related Searches -->
                @if($query && $relatedSearches)
                <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4">Related Searches</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($relatedSearches as $relatedSearch)
                        <a href="{{ route('search', ['q' => $relatedSearch]) }}" 
                           class="bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-700 px-3 py-2 rounded-lg text-sm hover:bg-blue-600 hover:text-white dark:hover:bg-blue-400 dark:hover:text-white transition-colors duration-200">
                            {{ $relatedSearch }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </main>

            <!-- Sidebar -->
            <aside class="lg:w-1/3" role="complementary">
                <div class="space-y-6 sticky top-6">
                    <!-- Categories -->
                    @if($categories->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-folder text-blue-500 mr-2"></i>
                            Browse Categories
                        </h3>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                            <a href="{{ route('categories.show', $category->slug) }}" 
                               class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 group">
                                <span class="text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200 font-medium">
                                    {{ $category->name }}
                                </span>
                                <span class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 text-xs font-bold px-2 py-1 rounded-full">
                                    {{ $category->posts_count }}
                                </span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Search Tips -->
                    <div class="bg-gradient-to-br from-green-600 to-teal-700 rounded-2xl shadow-lg p-6 text-white">
                        <h3 class="text-lg font-bold mb-4 flex items-center">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Search Tips
                        </h3>
                        <ul class="space-y-2 text-green-100 text-sm">
                            <li class="flex items-start">
                                <i class="fas fa-check mr-2 mt-1 text-xs"></i>
                                Use specific keywords for better results
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check mr-2 mt-1 text-xs"></i>
                                Try different word combinations
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check mr-2 mt-1 text-xs"></i>
                                Use category filters to narrow results
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check mr-2 mt-1 text-xs"></i>
                                Check spelling or try synonyms
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 text-center">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Need Help?</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm">
                            Can't find what you're looking for? Try these options.
                        </p>
                        <div class="space-y-3">
                            <a href="{{ route('posts.index') }}" 
                               class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 inline-flex items-center justify-center shadow-lg hover:shadow-xl">
                                <i class="fas fa-newspaper mr-2"></i>
                                All Articles
                            </a>
                            <a href="{{ route('categories.index') }}" 
                               class="w-full border-2 border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400 px-4 py-3 rounded-lg font-semibold hover:bg-blue-600 hover:text-white dark:hover:bg-blue-400 dark:hover:text-white transition-colors duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-th-large mr-2"></i>
                                All Categories
                            </a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Search suggestions styling */
.search-suggestion {
    padding: 0.75rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid #e5e7eb;
    transition: background-color 0.2s;
}

.search-suggestion:hover {
    background-color: #f3f4f6;
}

.search-suggestion:last-child {
    border-bottom: none;
}

.search-suggestion-type {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: capitalize;
}

.dark .search-suggestion {
    border-bottom-color: #374151;
}

.dark .search-suggestion:hover {
    background-color: #374151;
}

.dark .search-suggestion-type {
    color: #9ca3af;
}

/* Highlight animation */
mark {
    animation: highlight-pulse 2s ease-in-out;
}

@keyframes highlight-pulse {
    0% { background-color: #fef3c7; }
    50% { background-color: #fde68a; }
    100% { background-color: #fef3c7; }
}

.dark mark {
    animation: highlight-pulse-dark 2s ease-in-out;
}

@keyframes highlight-pulse-dark {
    0% { background-color: #92400e; }
    50% { background-color: #b45309; }
    100% { background-color: #92400e; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionsContainer = document.getElementById('search-suggestions');
    let currentRequest = null;

    // Focus on search input when page loads
    if (searchInput) {
        searchInput.focus();
        
        // Real-time search suggestions
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            // Cancel previous request if it exists
            if (currentRequest) {
                currentRequest.abort();
            }
            
            if (query.length < 2) {
                suggestionsContainer.classList.add('hidden');
                return;
            }
            
            // Show loading state
            suggestionsContainer.innerHTML = '<div class="search-suggestion text-gray-500">Loading...</div>';
            suggestionsContainer.classList.remove('hidden');
            
            // Make AJAX request for suggestions
            currentRequest = new AbortController();
            const signal = currentRequest.signal;
            
            fetch(`/search/suggest?q=${encodeURIComponent(query)}`, { signal })
                .then(response => response.json())
                .then(suggestions => {
                    if (suggestions.length === 0) {
                        suggestionsContainer.innerHTML = '<div class="search-suggestion text-gray-500">No suggestions found</div>';
                    } else {
                        suggestionsContainer.innerHTML = suggestions.map(suggestion => {
                            // Escape HTML to prevent XSS
                            const title = suggestion.title.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                            return `
                                <div class="search-suggestion" data-url="${suggestion.url}">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <div class="font-medium">${title}</div>
                                            <div class="search-suggestion-type">${suggestion.type}</div>
                                        </div>
                                        <i class="fas fa-arrow-right text-gray-400 text-xs"></i>
                                    </div>
                                </div>
                            `;
                        }).join('');
                        
                        // Add click handlers to suggestions
                        suggestionsContainer.querySelectorAll('.search-suggestion').forEach(suggestion => {
                            suggestion.addEventListener('click', function() {
                                window.location.href = this.dataset.url;
                            });
                        });
                    }
                })
                .catch(error => {
                    if (error.name !== 'AbortError') {
                        console.error('Search suggestion error:', error);
                        suggestionsContainer.classList.add('hidden');
                    }
                });
        });
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !suggestionsContainer.contains(event.target)) {
                suggestionsContainer.classList.add('hidden');
            }
        });
        
        // Keyboard navigation for suggestions
        searchInput.addEventListener('keydown', function(event) {
            if (!suggestionsContainer.classList.contains('hidden')) {
                const suggestions = suggestionsContainer.querySelectorAll('.search-suggestion');
                const activeSuggestion = suggestionsContainer.querySelector('.search-suggestion.bg-blue-100');
                
                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    if (!activeSuggestion) {
                        suggestions[0]?.classList.add('bg-blue-100', 'dark:bg-blue-900');
                    } else {
                        const next = activeSuggestion.nextElementSibling;
                        if (next) {
                            activeSuggestion.classList.remove('bg-blue-100', 'dark:bg-blue-900');
                            next.classList.add('bg-blue-100', 'dark:bg-blue-900');
                        }
                    }
                } else if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    if (activeSuggestion) {
                        const prev = activeSuggestion.previousElementSibling;
                        if (prev) {
                            activeSuggestion.classList.remove('bg-blue-100', 'dark:bg-blue-900');
                            prev.classList.add('bg-blue-100', 'dark:bg-blue-900');
                        }
                    }
                } else if (event.key === 'Enter' && activeSuggestion) {
                    event.preventDefault();
                    window.location.href = activeSuggestion.dataset.url;
                }
            }
        });
    }

    // Auto-submit filters when they change
    const categoryFilter = document.getElementById('category-filter');
    const sortFilter = document.getElementById('sort-filter');
    
    if (categoryFilter && sortFilter) {
        [categoryFilter, sortFilter].forEach(filter => {
            filter.addEventListener('change', function() {
                this.form.submit();
            });
        });
    }
});
</script>
@endpush