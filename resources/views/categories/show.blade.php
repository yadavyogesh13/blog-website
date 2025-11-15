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
    @if($posts->currentPage() > 1)
    <link rel="prev" href="{{ $posts->previousPageUrl() }}">
    @endif
    @if($posts->hasMorePages())
    <link rel="next" href="{{ $posts->nextPageUrl() }}">
    @endif
    
    <!-- Schema.org JSON-LD -->
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "@id": "{{ route('categories.show', $category->slug) }}#webpage",
        "url": "{{ route('categories.show', $category->slug) }}",
        "name": "{{ $seo['title'] }}",
        "description": "{{ $seo['description'] }}",
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
                    "name": "Categories",
                    "item": "{{ route('categories.index') }}"
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": "{{ $category->name }}"
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
                        "articleSection": "{{ $category->name }}",
                        "keywords": "{{ $post->seo->meta_keywords ?? $category->name }}",
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
    @endverbatim
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
                        <a href="{{ route('categories.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200" itemprop="item">
                            <span itemprop="name">Categories</span>
                        </a>
                        <meta itemprop="position" content="2" />
                    </li>
                    <li class="flex items-center" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <span class="text-gray-900 dark:text-white font-medium" itemprop="name">{{ $category->name }}</span>
                        <meta itemprop="position" content="3" />
                    </li>
                </ol>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Category Header -->
        <header class="text-center mb-12 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
            <div class="max-w-3xl mx-auto">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm font-semibold mb-4">
                    <i class="fas fa-folder mr-2"></i>
                    Category
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">{{ $category->name }}</h1>
                
                @if($category->description)
                <p class="text-xl text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                    {{ $category->description }}
                </p>
                @endif
                
                <div class="flex flex-wrap justify-center gap-6 text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center">
                        <i class="fas fa-newspaper mr-2"></i>
                        {{ $posts->total() }} {{ Str::plural('Article', $posts->total()) }}
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        Updated {{ $category->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </header>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <main class="lg:w-2/3" role="main">
                <!-- Posts Grid -->
                @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    @foreach($posts as $post)
                    <article class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700 group">
                        @if($post->featured_image)
                        <div class="h-48 overflow-hidden">
                            <img 
                                src="{{ $post->getFeaturedImageUrl() }}" 
                                alt="{{ $post->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                loading="lazy"
                            >
                        </div>
                        @endif
                        
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                <a href="{{ route('posts.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3 text-sm leading-relaxed">
                                {{ $post->excerpt }}
                            </p>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center">
                                        <img class="w-6 h-6 rounded-full mr-2" src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&background=random" alt="{{ $post->user->name }}">
                                        <span>{{ $post->user->name }}</span>
                                    </div>
                                    <span>{{ $post->published_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="flex items-center">
                                        <i class="fas fa-eye mr-1 text-xs"></i>
                                        {{ number_format($post->views) }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-clock mr-1 text-xs"></i>
                                        {{ $post->reading_time }} min
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    {{ $posts->onEachSide(1)->links() }}
                </div>
                @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <i class="fas fa-newspaper text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">No Articles Yet</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            There are no articles in this category yet. Check back soon for new content.
                        </p>
                        <div class="space-y-3">
                            <a href="{{ route('categories.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-300">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Categories
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </main>

            <!-- Sidebar -->
            <aside class="lg:w-1/3" role="complementary">
                <div class="space-y-6 sticky top-6">
                    <!-- Related Categories -->
                    @if($relatedCategories->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-tags text-green-500 mr-2"></i>
                            Related Categories
                        </h3>
                        <div class="space-y-3">
                            @foreach($relatedCategories as $relatedCategory)
                            <a href="{{ route('categories.show', $relatedCategory->slug) }}" 
                               class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-300 group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-folder text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="text-gray-900 dark:text-white font-medium group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                                            {{ $relatedCategory->name }}
                                        </span>
                                    </div>
                                </div>
                                <span class="bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 text-xs font-bold px-2 py-1 rounded-full">
                                    {{ $relatedCategory->posts_count }}
                                </span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Category Stats -->
                    <div class="bg-gradient-to-br from-purple-600 to-pink-700 rounded-2xl shadow-lg p-6 text-white">
                        <h3 class="text-lg font-bold mb-4 flex items-center">
                            <i class="fas fa-chart-pie mr-2"></i>
                            Category Insights
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-purple-100">Total Articles</span>
                                <span class="font-bold">{{ $posts->total() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-purple-100">Articles Showing</span>
                                <span class="font-bold">{{ $posts->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-purple-100">Last Updated</span>
                                <span class="font-bold text-sm">{{ $category->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Browse More -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 text-center">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Explore More</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm">
                            Discover more categories and find articles that match your interests.
                        </p>
                        <a href="{{ route('categories.index') }}" 
                           class="w-full bg-gradient-to-r from-blue-600 to-purple-700 text-white px-4 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-800 transition-all duration-300 inline-flex items-center justify-center shadow-lg hover:shadow-xl">
                            <i class="fas fa-th-large mr-2"></i>
                            All Categories
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection