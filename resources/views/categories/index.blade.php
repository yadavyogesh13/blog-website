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
    
    <!-- Additional Meta -->
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    
    <!-- Schema.org JSON-LD -->
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "@id": "{{ route('categories.index') }}#webpage",
        "url": "{{ route('categories.index') }}",
        "name": "Blog Categories - {{ config('app.name') }}",
        "description": "{{ $seo['description'] }}",
        "inLanguage": "en-US",
        "isPartOf": {
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
            }
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
                    "name": "Categories"
                }
            ]
        },
        "mainEntity": {
            "@type": "ItemList",
            "numberOfItems": {{ $categories->count() }},
            "itemListElement": [
                @foreach($categories as $index => $category)
                {
                    "@type": "ListItem",
                    "position": {{ $index + 1 }},
                    "item": {
                        "@type": "CollectionPage",
                        "@id": "{{ route('categories.show', $category->slug) }}#webpage",
                        "url": "{{ route('categories.show', $category->slug) }}",
                        "name": "{{ $category->name }} Articles",
                        "description": "{{ $category->description ?? 'Browse ' . $category->name . ' articles' }}",
                        "numberOfItems": {{ $category->posts_count }},
                        "mainEntity": {
                            "@type": "ItemList",
                            "numberOfItems": {{ $category->posts_count }},
                            "itemListElement": []
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
    <!-- Schema.org Breadcrumb Markup (Visible) -->
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
                        <span class="text-gray-900 dark:text-white font-medium" itemprop="name">Categories</span>
                        <meta itemprop="position" content="2" />
                    </li>
                </ol>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <header class="text-center mb-12" itemscope itemtype="https://schema.org/WebPage">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4" itemprop="headline">
                Blog Categories
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto mb-6" itemprop="description">
                Explore our comprehensive collection of articles organized by topics. Find exactly what you're looking for or discover new interests.
            </p>
            
            <!-- Stats -->
            <div class="flex flex-wrap justify-center gap-6 mb-8">
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $categories->count() }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Categories</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-green-600 dark:text-green-400">{{ $totalPosts }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Articles</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-purple-600 dark:text-purple-400">
                        {{ $popularCategories->first()->posts_count ?? 0 }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Most Popular</div>
                </div>
            </div>
        </header>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <main class="lg:w-2/3" role="main">
                <!-- Categories Grid -->
                @if($categories->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-12" itemscope itemtype="https://schema.org/ItemList">
                    @foreach($categories as $category)
                    <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden group border border-gray-200 dark:border-gray-700"
                             itemprop="itemListElement" itemscope itemtype="https://schema.org/CollectionPage">
                        <div class="relative overflow-hidden">
                            <!-- Category Image/Icon -->
                            <div class="h-32 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                                <i class="fas fa-folder text-white text-4xl opacity-80 group-hover:opacity-100 transition-opacity duration-300"></i>
                            </div>
                            
                            <!-- Posts Count Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="bg-white dark:bg-gray-900 text-blue-600 dark:text-blue-400 text-sm font-bold px-3 py-1 rounded-full shadow-lg">
                                    {{ $category->posts_count }} {{ $category->posts_count == 1 ? 'Post' : 'Posts' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300 line-clamp-2"
                                itemprop="name">
                                <a href="{{ route('categories.show', $category->slug) }}" itemprop="url" class="hover:no-underline">
                                    {{ $category->name }}
                                </a>
                            </h2>
                            
                            @if($category->description)
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3 text-sm leading-relaxed" itemprop="description">
                                {{ $category->description }}
                            </p>
                            @else
                            <p class="text-gray-500 dark:text-gray-500 mb-4 text-sm italic">
                                Explore our collection of {{ $category->name }} articles and blog posts.
                            </p>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <a href="{{ route('categories.show', $category->slug) }}" 
                                   class="inline-flex items-center text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-300 group/link text-sm">
                                    Browse Posts
                                    <i class="fas fa-arrow-right ml-2 transform group-hover/link:translate-x-1 transition-transform duration-300"></i>
                                </a>
                                
                                @if($category->posts_count > 0)
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    Updated {{ $category->updated_at->diffForHumans() }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Hidden Microdata -->
                        <meta itemprop="numberOfItems" content="{{ $category->posts_count }}">
                        <link itemprop="isPartOf" href="{{ route('categories.index') }}#webpage" />
                    </article>
                    @endforeach
                </div>
                @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <i class="fas fa-folder-open text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">No Categories Available</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                            We're organizing our content into categories. Check back soon to explore articles by topic.
                        </p>
                        <a href="{{ route('posts.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-newspaper mr-2"></i>
                            Browse All Articles
                        </a>
                    </div>
                </div>
                @endif
            </main>

            <!-- Sidebar -->
            <aside class="lg:w-1/3" role="complementary">
                <div class="space-y-6 sticky top-6">
                    <!-- Popular Categories -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-fire text-orange-500 mr-2"></i>
                            Popular Categories
                        </h3>
                        <div class="space-y-3">
                            @foreach($popularCategories as $popularCategory)
                            <a href="{{ route('categories.show', $popularCategory->slug) }}" 
                               class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-300 group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-folder text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="text-gray-900 dark:text-white font-medium group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                                            {{ $popularCategory->name }}
                                        </span>
                                    </div>
                                </div>
                                <span class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 text-xs font-bold px-2 py-1 rounded-full">
                                    {{ $popularCategory->posts_count }}
                                </span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-gradient-to-br from-blue-600 to-purple-700 rounded-2xl shadow-lg p-6 text-white">
                        <h3 class="text-lg font-bold mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Content Overview
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-blue-100">Total Categories</span>
                                <span class="font-bold">{{ $categories->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-100">Total Articles</span>
                                <span class="font-bold">{{ $totalPosts }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-100">Most Active</span>
                                <span class="font-bold text-sm">{{ $popularCategories->first()->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 text-center">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Can't Find Your Topic?</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm">
                            Browse all articles or use our powerful search to find specific content.
                        </p>
                        <div class="space-y-3">
                            <a href="{{ route('posts.index') }}" 
                               class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-300 inline-flex items-center justify-center shadow-lg hover:shadow-xl">
                                <i class="fas fa-newspaper mr-2"></i>
                                Browse All Articles
                            </a>
                            <a href="{{ route('search') }}" 
                               class="w-full border-2 border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400 px-4 py-3 rounded-lg font-semibold hover:bg-blue-600 hover:text-white dark:hover:bg-blue-400 dark:hover:text-white transition-colors duration-300 inline-flex items-center justify-center">
                                <i class="fas fa-search mr-2"></i>
                                Search Articles
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

/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark ::-webkit-scrollbar-track {
    background: #1e293b;
}

.dark ::-webkit-scrollbar-thumb {
    background: #475569;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe category cards
    document.querySelectorAll('article').forEach((card) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Smooth loading for images
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.3s ease';
    });
});
</script>
@endpush