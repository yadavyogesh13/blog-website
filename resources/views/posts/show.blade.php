@extends('layouts.app')

@section('title', $post->getMetaTitle())

@section('meta')
    <!-- Primary Meta Tags -->
    <meta name="title" content="{{ $post->getMetaTitle() }}">
    <meta name="description" content="{{ $post->getMetaDescription() }}">
    <meta name="keywords" content="{{ $post->seo->meta_keywords ?? $post->category->name }}, {{ config('app.name') }}">
    <meta name="author" content="{{ $post->user->name }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $post->getMetaTitle() }}">
    <meta property="og:description" content="{{ $post->getMetaDescription() }}">
    <meta property="og:image" content="{{ $post->getFeaturedImageUrl() }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $post->getMetaTitle() }}">
    <meta property="twitter:description" content="{{ $post->getMetaDescription() }}">
    <meta property="twitter:image" content="{{ $post->getFeaturedImageUrl() }}">
    <meta property="twitter:creator" content="@username">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}" />
    
    <!-- Article Specific Meta -->
    <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $post->user->name }}">
    <meta property="article:section" content="{{ $post->category->name }}">
    
    <!-- JSON-LD Structured Data -->
    @verbatim
        
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BlogPosting",
        "headline": "{{ $post->title }}",
        "description": "{{ $post->getMetaDescription() }}",
        "image": "{{ $post->getFeaturedImageUrl() }}",
        "author": {
            "@type": "Person",
            "name": "{{ $post->user->name }}",
            "url": "{{ url('/author/' . $post->user->id) }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "{{ config('app.name') }}",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('images/logo.png') }}"
            }
        },
        "datePublished": "{{ $post->published_at->toIso8601String() }}",
        "dateModified": "{{ $post->updated_at->toIso8601String() }}",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ url()->current() }}"
        },
        "articleSection": "{{ $post->category->name }}",
        "keywords": "{{ $post->seo->meta_keywords ?? $post->category->name }}",
        "wordCount": "{{ str_word_count(strip_tags($post->content)) }}"
    }
    </script>
    
    <!-- Breadcrumb Structured Data -->
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
                "name": "{{ $post->category->name }}",
                "item": "{{ route('categories.show', $post->category->slug) }}"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": "{{ $post->title }}",
                "item": "{{ url()->current() }}"
            }
        ]
    }
    </script>
    @endverbatim

@endsection

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 md:mb-8" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-xs mx-2"></i>
                    <a href="{{ route('categories.show', $post->category->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                        {{ $post->category->name }}
                    </a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-xs mx-2"></i>
                    <span class="text-gray-900 dark:text-white font-medium truncate max-w-[200px] md:max-w-none">
                        {{ Str::limit($post->title, 40) }}
                    </span>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <main class="lg:w-2/3">
                <article class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                    <!-- Article Header -->
                    <header class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700">
                        <div class="mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                {{ $post->category->name }}
                            </span>
                            @if($post->is_featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 ml-2">
                                <i class="fas fa-star mr-1"></i>Featured
                            </span>
                            @endif
                        </div>
                        
                        <h1 class="text-2xl md:text-4xl font-bold text-gray-900 dark:text-white leading-tight mb-4">
                            {{ $post->title }}
                        </h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full mr-2" src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&background=random" alt="{{ $post->user->name }}">
                                <span>By {{ $post->user->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                <time datetime="{{ $post->published_at->toIso8601String() }}">
                                    {{ $post->published_at->format('F d, Y') }}
                                </time>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-eye mr-2"></i>
                                <span>{{ number_format($post->views) }} views</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                <span>{{ $post->reading_time }} min read</span>
                            </div>
                        </div>
                    </header>

                    <!-- Featured Image -->
                    @if($post->featured_image)
                    <div class="w-full">
                        <img 
                            src="{{ Storage::url($post->featured_image) }}" 
                            alt="{{ $post->title }}"
                            class="w-full h-auto max-h-[500px] object-cover"
                            loading="eager"
                        >
                    </div>
                    @endif

                    <!-- Article Content -->
                    <div class="p-6 md:p-8">
                        <div class="prose prose-lg max-w-none dark:prose-invert 
                                  prose-headings:text-gray-900 dark:prose-headings:text-white
                                  prose-p:text-gray-700 dark:prose-p:text-gray-300
                                  prose-strong:text-gray-900 dark:prose-strong:text-white
                                  prose-em:text-gray-700 dark:prose-em:text-gray-300
                                  prose-blockquote:text-gray-600 dark:prose-blockquote:text-gray-400
                                  prose-ul:text-gray-700 dark:prose-ul:text-gray-300
                                  prose-ol:text-gray-700 dark:prose-ol:text-gray-300
                                  prose-li:text-gray-700 dark:prose-li:text-gray-300
                                  prose-code:text-gray-800 dark:prose-code:text-gray-200
                                  prose-pre:bg-gray-100 dark:prose-pre:bg-gray-800
                                  prose-a:text-blue-600 dark:prose-a:text-blue-400
                                  hover:prose-a:text-blue-800 dark:hover:prose-a:text-blue-300
                                  prose-img:rounded-xl prose-img:shadow-sm">
                            {!! $post->content !!}
                        </div>
                    </div>

                    <!-- Article Footer -->
                    <footer class="px-6 md:px-8 py-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <!-- Tags -->
                        @if($post->tags)
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Tags:</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $post->tags) as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    #{{ trim($tag) }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Social Sharing -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border border-gray-200 dark:border-gray-700">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Share this article:</h4>
                            <div class="flex flex-wrap gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm">
                                    <i class="fab fa-facebook-f mr-2"></i>Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors duration-200 text-sm">
                                    <i class="fab fa-twitter mr-2"></i>Twitter
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors duration-200 text-sm">
                                    <i class="fab fa-linkedin-in mr-2"></i>LinkedIn
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200 text-sm">
                                    <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                                </a>
                            </div>
                        </div>
                    </footer>
                </article>

                <!-- Related Posts -->
                @if($related_posts->count() > 0)
                <section class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Related Articles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($related_posts as $related_post)
                        <article class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-all duration-300">
                            @if($related_post->featured_image)
                            <div class="h-48 overflow-hidden">
                                <img 
                                    src="{{ Storage::url($related_post->featured_image) }}" 
                                    alt="{{ $related_post->title }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                >
                            </div>
                            @endif
                            <div class="p-4">
                                <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded mb-2">
                                    {{ $related_post->category->name }}
                                </span>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                    <a href="{{ route('posts.show', $related_post->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                        {{ $related_post->title }}
                                    </a>
                                </h3>
                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-3">
                                    <span>{{ $related_post->published_at->format('M d, Y') }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $related_post->reading_time }} min read</span>
                                </div>
                                <a href="{{ route('posts.show', $related_post->slug) }}" 
                                   class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors duration-200">
                                    Read More
                                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </section>
                @endif
            </main>

            <!-- Sidebar -->
            <aside class="lg:w-1/3">
                <div class="space-y-6">
                    <!-- Recent Posts Widget -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-300">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Recent Posts
                        </h3>
                        <div class="space-y-4">
                            @foreach($recent_posts as $recent_post)
                            <article class="flex items-start space-x-3 group">
                                @if($recent_post->featured_image)
                                <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden">
                                    <img 
                                        src="{{ Storage::url($recent_post->featured_image) }}" 
                                        alt="{{ $recent_post->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"
                                        loading="lazy"
                                    >
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                        <a href="{{ route('posts.show', $recent_post->slug) }}">
                                            {{ $recent_post->title }}
                                        </a>
                                    </h4>
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <span>{{ $recent_post->published_at->format('M d') }}</span>
                                        <span class="mx-1">•</span>
                                        <span>{{ $recent_post->reading_time }} min</span>
                                    </div>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    </div>

                    <!-- Categories Widget -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-colors duration-300">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                            Categories
                        </h3>
                        <div class="space-y-2">
                            @foreach($categories as $category)
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

                    <!-- Newsletter Widget -->
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl shadow-sm p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">Stay Updated</h3>
                        <p class="text-blue-100 text-sm mb-4">Get the latest posts delivered right to your inbox.</p>
                        <form class="space-y-3">
                            <input type="email" 
                                   placeholder="Enter your email" 
                                   class="w-full px-4 py-2 rounded-lg border border-blue-500 bg-blue-500 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-600 dark:border-blue-600">
                            <button type="submit" 
                                    class="w-full bg-white text-blue-600 font-semibold py-2 px-4 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                Subscribe
                            </button>
                        </form>
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
    
    .prose {
        max-width: none;
    }
    
    .prose img {
        margin: 1.5rem auto;
    }
    
    .prose h2, .prose h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .prose p {
        margin-bottom: 1.25rem;
        line-height: 1.7;
    }
    
    .prose ul, .prose ol {
        margin-bottom: 1.25rem;
    }
    
    .prose blockquote {
        border-left: 4px solid #3b82f6;
        padding-left: 1.5rem;
        font-style: italic;
    }
    
    /* Ensure smooth transitions for dark mode */
    * {
        transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
    }
</style>
@endpush