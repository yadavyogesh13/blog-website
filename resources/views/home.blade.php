@extends('layouts.app')

@section('title', $seo['title'] ?? 'BlogSite')

@section('meta')
    <meta name="description" content="{{ $seo['description'] }}">
    <meta name="keywords" content="{{ $seo['keywords'] }}">
    <link rel="canonical" href="{{ $seo['canonical'] }}">
@endsection

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-serif font-bold text-gray-900 dark:text-white mb-6">
                Stay curious.
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto">
                Discover stories, thinking, and expertise from writers on any topic.
            </p>
            <div class="space-x-4">
                <a href="{{ route('posts.index') }}" class="bg-accent-500 text-white px-8 py-4 rounded-full text-lg font-medium hover:bg-accent-600 transition-colors inline-block">
                    Start reading
                </a>
                <a href="{{ route('categories.index') }}" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-8 py-4 rounded-full text-lg font-medium hover:border-gray-400 dark:hover:border-gray-500 transition-colors inline-block">
                    Explore topics
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Trending Posts -->
@if($popular_posts->count() > 0)
<section class="py-12 bg-white dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center mb-8">
            <div class="flex items-center bg-accent-50 dark:bg-accent-900/20 text-accent-700 dark:text-accent-400 px-3 py-1 rounded-full">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-semibold uppercase tracking-wide">TRENDING ON BLOGSITE</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($popular_posts as $index => $post)
            <article class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow">
                <div class="flex space-x-4">
                    <div class="flex-shrink-0">
                        <span class="text-3xl font-serif font-bold text-gray-200 dark:text-gray-700">0{{ $index + 1 }}</span>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2 mb-3">
                            <img 
                                src="{{ $post->user->avatar ?? '/images/default-avatar.png' }}" 
                                alt="{{ $post->user->name }}"
                                class="w-6 h-6 rounded-full object-cover"
                            >
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $post->user->name }}</span>
                        </div>
                        
                        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 hover:text-accent-600 dark:hover:text-accent-400 transition-colors">
                            <a href="{{ route('posts.show', $post->slug) }}">
                                {{ $post->title }}
                            </a>
                        </h3>
                        
                        <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                            <time datetime="{{ $post->published_at->toISOString() }}">
                                {{ $post->published_at->format('M j') }}
                            </time>
                            <span>·</span>
                            <span>{{ $post->reading_time }} min read</span>
                            <span>·</span>
                            <span>{{ $post->views }} views</span>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Main Posts Feed -->
        <main class="lg:w-2/3">
            <div class="space-y-8">
                @foreach($recent_posts as $post)
                <article class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-3">
                                <img 
                                    src="{{ $post->user->avatar ?? '/images/default-avatar.png' }}" 
                                    alt="{{ $post->user->name }}"
                                    class="w-6 h-6 rounded-full object-cover"
                                >
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $post->user->name }}</span>
                            </div>
                            
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 hover:text-accent-600 dark:hover:text-accent-400 transition-colors">
                                <a href="{{ route('posts.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            
                            <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                                {{ $post->excerpt }}
                            </p>
                            
                            <div class="flex items-center justify-between flex-wrap gap-3">
                                <div class="flex items-center space-x-3 text-sm text-gray-500 dark:text-gray-400 flex-wrap">
                                    <time datetime="{{ $post->published_at->toISOString() }}">
                                        {{ $post->published_at->format('M j, Y') }}
                                    </time>
                                    <span>·</span>
                                    <span>{{ $post->reading_time }} min read</span>
                                    @if($post->category)
                                    <span>·</span>
                                    <a href="{{ route('categories.show', $post->category->slug) }}" class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full text-xs hover:bg-accent-100 dark:hover:bg-accent-900/20 hover:text-accent-700 dark:hover:text-accent-400 transition-colors">
                                        {{ $post->category->name }}
                                    </a>
                                    @endif
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <button class="like-btn text-gray-400 hover:text-red-500 transition-colors p-1 flex items-center space-x-1" data-post-id="{{ $post->id }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span class="text-xs like-count">{{ $post->likes_count ?? 0 }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        @if($post->featured_image)
                        <div class="flex-shrink-0 md:w-48">
                            <a href="{{ route('posts.show', $post->slug) }}">
                                <img 
                                    src="{{ Storage::url($post->featured_image) }}" 
                                    alt="{{ $post->title }}"
                                    class="w-full h-32 md:h-40 object-cover rounded-lg"
                                    loading="lazy"
                                >
                            </a>
                        </div>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>

            @if($recent_posts->count() > 0)
            <div class="mt-12 text-center">
                <a href="{{ route('posts.index') }}" class="border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-full text-sm font-medium hover:border-gray-400 dark:hover:border-gray-500 transition-colors inline-block">
                    Load more articles
                </a>
            </div>
            @endif

            @if($recent_posts->count() === 0)
            <div class="text-center py-16">
                <div class="text-gray-300 dark:text-gray-600 mb-4">
                    <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-400 mb-2">No articles yet</h3>
                <p class="text-gray-500 dark:text-gray-500 mb-6">Check back later for new content.</p>
                <a href="{{ route('posts.index') }}" class="bg-accent-500 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-accent-600 transition-colors inline-block">
                    Browse all articles
                </a>
            </div>
            @endif
        </main>

        <!-- Sidebar -->
        <aside class="lg:w-1/3 space-y-8">
            <!-- Discover More -->
            <section class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Discover more</h3>
                <div class="space-y-3">
                    @foreach($categories->take(8) as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                        <span class="text-gray-700 dark:text-gray-200 group-hover:text-gray-900 dark:group-hover:text-white font-medium transition-colors">
                            {{ $category->name }}
                        </span>
                        <span class="text-gray-500 dark:text-gray-400 text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full group-hover:bg-accent-100 dark:group-hover:bg-accent-900/20 group-hover:text-accent-700 dark:group-hover:text-accent-400 transition-colors">
                            {{ $category->posts_count }}
                        </span>
                    </a>
                    @endforeach
                </div>
                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('categories.index') }}" class="text-accent-600 dark:text-accent-400 hover:text-accent-700 dark:hover:text-accent-300 font-medium text-sm flex items-center">
                        See all topics
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </section>
        </aside>
    </div>
</div>
@endsection