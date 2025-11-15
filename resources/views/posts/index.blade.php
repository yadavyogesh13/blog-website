@extends('layouts.app')

@section('title', $seo['title'] ?? 'All Articles - ' . config('app.name'))

@section('meta')
    <!-- Primary Meta Tags -->
    <meta name="description" content="{{ $seo['description'] ?? 'Browse all articles on various topics including technology, lifestyle, business and more.' }}">
    <meta name="keywords" content="{{ $seo['keywords'] ?? 'articles, blog posts, stories, writing, technology, lifestyle, business' }}">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}" />
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seo['title'] ?? 'All Articles - ' . config('app.name') }}">
    <meta property="og:description" content="{{ $seo['description'] ?? 'Browse all articles on various topics including technology, lifestyle, business and more.' }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $seo['title'] ?? 'All Articles - ' . config('app.name') }}">
    <meta property="twitter:description" content="{{ $seo['description'] ?? 'Browse all articles on various topics including technology, lifestyle, business and more.' }}">
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="twitter:creator" content="@yourtwitterhandle">
    
    <!-- Additional Meta -->
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    <meta name="rating" content="general">
    <meta name="distribution" content="global">
    
    <!-- Schema.org JSON-LD -->
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "@id": "{{ route('posts.index') }}#webpage",
        "url": "{{ route('posts.index') }}",
        "name": "{{ $seo['title'] ?? 'All Articles - ' . config('app.name') }}",
        "description": "{{ $seo['description'] ?? 'Browse all articles on various topics including technology, lifestyle, business and more.' }}",
        "inLanguage": "en-US",
        "isPartOf": {
            "@type": "WebSite",
            "@id": "{{ url('/') }}#website",
            "url": "{{ url('/') }}",
            "name": "{{ config('app.name') }}",
            "description": "{{ config('app.description', 'Your blog description') }}"
        },
        "breadcrumb": {
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": "{{ url('/') }}"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "Articles"
                }
            ]
        },
        "mainEntity": {
            "@type": "ItemList",
            "numberOfItems": {{ $posts->total() }},
            "itemListElement": [
                @foreach($posts as $index => $post)
                {
                    "@type": "ListItem",
                    "position": {{ $index + 1 }},
                    "item": {
                        "@type": "Article",
                        "@id": "{{ route('posts.show', $post->slug) }}#article",
                        "url": "{{ route('posts.show', $post->slug) }}",
                        "name": "{{ $post->title }}",
                        "headline": "{{ $post->title }}",
                        "description": "{{ $post->getMetaDescription() }}",
                        "datePublished": "{{ $post->published_at->toIso8601String() }}",
                        "dateModified": "{{ $post->updated_at->toIso8601String() }}",
                        "author": {
                            "@type": "Person",
                            "@id": "{{ url('/author/' . $post->user->id) }}",
                            "name": "{{ $post->user->name }}"
                        },
                        "publisher": {
                            "@type": "Organization",
                            "@id": "{{ url('/') }}#organization",
                            "name": "{{ config('app.name') }}",
                            "logo": {
                                "@type": "ImageObject",
                                "url": "{{ asset('images/logo.png') }}"
                            }
                        },
                        "image": {
                            "@type": "ImageObject",
                            "url": "{{ $post->getFeaturedImageUrl() }}",
                            "width": "800",
                            "height": "400"
                        },
                        "articleSection": "{{ $post->category->name }}",
                        "keywords": "{{ $post->seo->meta_keywords ?? $post->category->name }}",
                        "mainEntityOfPage": {
                            "@type": "WebPage",
                            "@id": "{{ route('posts.show', $post->slug) }}"
                        }
                    }
                }{{ !$loop->last ? ',' : '' }}
                @endforeach
            ]
        }
    }
    </script>
    
    <!-- Breadcrumb Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ url('/') }}"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "Articles",
                "item": "{{ route('posts.index') }}"
            }
        ]
    }
    </script>
    
    <!-- Website Schema -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "@id": "{{ url('/') }}#website",
        "url": "{{ url('/') }}",
        "name": "{{ config('app.name') }}",
        "description": "{{ config('app.description', 'Your blog description') }}",
        "publisher": {
            "@type": "Organization",
            "@id": "{{ url('/') }}#organization",
            "name": "{{ config('app.name') }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('images/logo.png') }}"
            }
        },
        "potentialAction": {
            "@type": "SearchAction",
            "target": "{{ route('search') }}?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    @endverbatim
    
    <!-- Pagination SEO -->
    @if($posts->currentPage() > 1)
    <link rel="prev" href="{{ $posts->previousPageUrl() }}">
    @endif
    @if($posts->hasMorePages())
    <link rel="next" href="{{ $posts->nextPageUrl() }}">
    @endif
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Schema.org Breadcrumb Markup (Visible) -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700" aria-label="Breadcrumb">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-3">
                <ol class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" itemprop="item">
                            <span itemprop="name">Home</span>
                        </a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <span class="text-gray-900 dark:text-white font-medium" itemprop="name">Articles</span>
                    </li>
                </ol>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Header with Rich Snippet Markup -->
        <header class="text-center mb-12" itemscope itemtype="https://schema.org/WebPage">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4" itemprop="headline">
                All Articles
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto" itemprop="description">
                Discover insightful articles on various topics. Learn something new every day.
            </p>
            
            <!-- Hidden Microdata -->
            <meta itemprop="inLanguage" content="en-US">
            <meta itemprop="datePublished" content="{{ now()->toIso8601String() }}">
            <meta itemprop="dateModified" content="{{ now()->toIso8601String() }}">
        </header>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <main class="lg:w-3/4" role="main" itemscope itemtype="https://schema.org/Blog">
                <!-- Filters -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter by:</span>
                            <select id="category-filter" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->posts_count }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sort by:</span>
                            <select id="sort-filter" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                <option value="latest">Latest</option>
                                <option value="popular">Most Popular</option>
                                <option value="featured">Featured</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Posts Grid -->
                @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8" itemprop="blogPosts" itemscope itemtype="https://schema.org/BlogPosting">
                    @foreach($posts as $post)
                    <article class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-all duration-300 group" 
                             itemprop="itemListElement" itemscope itemtype="https://schema.org/Article">
                        @if($post->featured_image)
                        <div class="h-48 overflow-hidden">
                            <img 
                                src="{{ $post->getFeaturedImageUrl() }}" 
                                alt="{{ $post->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                loading="lazy"
                                itemprop="image"
                            >
                        </div>
                        @endif
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200" itemprop="articleSection">
                                    {{ $post->category->name }}
                                </span>
                                @if($post->is_featured)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">
                                    <i class="fas fa-star mr-1 text-xs"></i>Featured
                                </span>
                                @endif
                            </div>
                            
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200" itemprop="headline">
                                <a href="{{ route('posts.show', $post->slug) }}" itemprop="url">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3" itemprop="description">
                                {{ $post->excerpt }}
                            </p>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center space-x-4" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                    <div class="flex items-center">
                                        <img class="w-6 h-6 rounded-full mr-2" src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&background=random" alt="{{ $post->user->name }}" itemprop="image">
                                        <span itemprop="name">{{ $post->user->name }}</span>
                                    </div>
                                    <time datetime="{{ $post->published_at->toIso8601String() }}" itemprop="datePublished">
                                        {{ $post->published_at->format('M d, Y') }}
                                    </time>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="flex items-center" itemprop="interactionStatistic" itemscope itemtype="https://schema.org/InteractionCounter">
                                        <i class="fas fa-eye mr-1 text-xs"></i>
                                        <span itemprop="userInteractionCount">{{ number_format($post->views) }}</span> views
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-clock mr-1 text-xs"></i>
                                        <span itemprop="timeRequired">{{ $post->reading_time }} min read</span>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Hidden Microdata -->
                            <meta itemprop="dateModified" content="{{ $post->updated_at->toIso8601String() }}">
                            <meta itemprop="mainEntityOfPage" content="{{ route('posts.show', $post->slug) }}">
                            <meta itemprop="publisher" content="{{ config('app.name') }}">
                        </div>
                    </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    {{ $posts->onEachSide(1)->links() }}
                </div>
                @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <i class="fas fa-newspaper text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No articles found</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            There are no articles matching your criteria at the moment.
                        </p>
                        <a href="{{ route('posts.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-redo mr-2"></i>
                            Reset Filters
                        </a>
                    </div>
                </div>
                @endif
            </main>

            <!-- Sidebar -->
            <aside class="lg:w-1/4" role="complementary">
                <!-- Search Widget -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Search Articles</h3>
                    <form action="{{ route('search') }}" method="GET" role="search">
                        <div class="relative">
                            <input type="text" 
                                   name="q" 
                                   placeholder="Search articles..." 
                                   class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                                   value="{{ request('q') }}"
                                   aria-label="Search articles">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Popular Categories -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Popular Categories
                    </h3>
                    <div class="space-y-3">
                        @foreach($categories->take(8) as $category)
                        <a href="{{ route('categories.show', $category->slug) }}" 
                           class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 group">
                            <span class="text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                {{ $category->name }}
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                {{ $category->posts_count }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl shadow-sm p-6 text-white mb-6">
                    <h3 class="text-lg font-semibold mb-2">Stay Updated</h3>
                    <p class="text-blue-100 text-sm mb-4">Get the latest articles delivered to your inbox.</p>
                    <form class="space-y-3" action="/newsletter" method="POST">
                        @csrf
                        <input type="email" 
                               name="email"
                               placeholder="Enter your email" 
                               class="w-full px-4 py-2 rounded-lg border border-blue-500 bg-blue-500 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-600 dark:border-blue-600"
                               required
                               aria-label="Email for newsletter">
                        <button type="submit" 
                                class="w-full bg-white text-blue-600 font-semibold py-2 px-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            Subscribe
                        </button>
                    </form>
                </div>

                <!-- Popular Tags -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Popular Tags
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $popularTags = ['Technology', 'Programming', 'Web Development', 'Laravel', 'JavaScript', 'PHP', 'CSS', 'HTML', 'React', 'Vue', 'Node.js', 'Database'];
                        @endphp
                        @foreach($popularTags as $tag)
                        <a href="{{ route('search') }}?q={{ urlencode($tag) }}" 
                           class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200">
                            #{{ $tag }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryFilter = document.getElementById('category-filter');
        const sortFilter = document.getElementById('sort-filter');
        
        function updateFilters() {
            const category = categoryFilter.value;
            const sort = sortFilter.value;
            
            let url = new URL(window.location.href);
            
            if (category) {
                url.searchParams.set('category', category);
            } else {
                url.searchParams.delete('category');
            }
            
            if (sort && sort !== 'latest') {
                url.searchParams.set('sort', sort);
            } else {
                url.searchParams.delete('sort');
            }
            
            // Reset to page 1 when changing filters
            url.searchParams.delete('page');
            
            window.location.href = url.toString();
        }
        
        categoryFilter.addEventListener('change', updateFilters);
        sortFilter.addEventListener('change', updateFilters);
        
        // Set current sort value from URL
        const urlParams = new URLSearchParams(window.location.search);
        const currentSort = urlParams.get('sort') || 'latest';
        sortFilter.value = currentSort;
    });
</script>
@endpush