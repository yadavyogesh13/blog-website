@extends('layouts.app')

@section('title', $query ? "Search Results for \"{$query}\"" : 'Search')

@section('meta')
    <meta name="description" content="{{ $query ? 'Search results for ' . $query : 'Search our blog for articles and content' }}">
    @if($query)
    <link rel="canonical" href="{{ url('/search?q=' . urlencode($query)) }}">
    @endif
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <main class="lg:w-2/3">
                <!-- Search Header -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">
                        @if($query)
                            Search Results for "{{ $query }}"
                        @else
                            Search Articles
                        @endif
                    </h1>

                    <!-- Search Form -->
                    <form action="{{ route('search') }}" method="GET" class="max-w-2xl">
                        <div class="flex gap-2">
                            <input 
                                type="text" 
                                name="q" 
                                value="{{ $query }}"
                                placeholder="Search articles, topics, or keywords..."
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                required
                            >
                            <button 
                                type="submit"
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 flex items-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Search Results -->
                @if($query)
                    <div class="mb-6">
                        <p class="text-gray-600">
                            Found {{ $posts->total() }} result{{ $posts->total() === 1 ? '' : 's' }} for "{{ $query }}"
                        </p>
                    </div>

                    @if($posts->count() > 0)
                        <div class="space-y-6">
                            @foreach($posts as $post)
                            <article class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden group">
                                @if($post->featured_image)
                                <div class="relative overflow-hidden">
                                    <img 
                                        src="{{ Storage::url($post->featured_image) }}" 
                                        alt="{{ $post->title }}"
                                        class="w-full h-48 object-cover group-hover:scale-105 transition duration-500"
                                        loading="lazy"
                                    >
                                </div>
                                @endif
                                
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                            {{ $post->category->name ?? 'Uncategorized' }}
                                        </span>
                                        <time datetime="{{ $post->published_at->toISOString() }}" class="text-sm text-gray-500">
                                            {{ $post->published_at->format('M d, Y') }}
                                        </time>
                                    </div>
                                    
                                    <h2 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition duration-200">
                                        <a href="{{ route('posts.show', $post->slug) }}" class="hover:no-underline">
                                            {!! $this->highlightText($post->title, $query) !!}
                                        </a>
                                    </h2>
                                    
                                    <p class="text-gray-600 mb-4 line-clamp-3">
                                        {!! $this->highlightText($post->excerpt, $query) !!}
                                    </p>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                                            <img 
                                                src="{{ $post->user->avatar ?? asset('images/default-avatar.png') }}" 
                                                alt="{{ $post->user->name ?? 'Unknown Author' }}"
                                                class="w-5 h-5 rounded-full"
                                            >
                                            <span>{{ $post->user->name ?? 'Unknown Author' }}</span>
                                        </div>
                                        <a href="{{ route('posts.show', $post->slug) }}" 
                                           class="text-blue-600 hover:text-blue-700 text-sm font-semibold transition duration-200">
                                            Read More →
                                        </a>
                                    </div>
                                </div>
                            </article>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($posts->hasPages())
                        <div class="mt-8">
                            {{ $posts->links() }}
                        </div>
                        @endif
                    @else
                        <!-- No Results -->
                        <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                            <div class="text-gray-400 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">No results found</h3>
                            <p class="text-gray-500 mb-6">Try different keywords or browse our categories</p>
                            <a href="{{ route('posts.index') }}" 
                               class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                                Browse All Articles
                            </a>
                        </div>
                    @endif
                @else
                    <!-- Empty Search State -->
                    <div class="text-center py-12 bg-white rounded-xl shadow-sm">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">What are you looking for?</h3>
                        <p class="text-gray-500">Enter keywords to search through our articles and content</p>
                    </div>
                @endif
            </main>

            <!-- Sidebar -->
            <aside class="lg:w-1/3 space-y-6">
                <!-- Categories -->
                @if($categories->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">Categories</h3>
                    <div class="space-y-2">
                        @foreach($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}" 
                           class="flex items-center justify-between p-3 rounded-lg hover:bg-blue-50 transition duration-200 group">
                            <span class="text-gray-700 group-hover:text-blue-600 font-medium transition duration-200">
                                {{ $category->name }}
                            </span>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                                {{ $category->posts_count }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Recent Posts -->
                @if($recent_posts->count() > 0)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">Recent Posts</h3>
                    <div class="space-y-4">
                        @foreach($recent_posts as $post)
                        <article class="flex space-x-3 group">
                            @if($post->featured_image)
                            <div class="flex-shrink-0">
                                <img 
                                    src="{{ Storage::url($post->featured_image) }}" 
                                    alt="{{ $post->title }}"
                                    class="w-16 h-16 rounded-lg object-cover group-hover:scale-105 transition duration-200"
                                    loading="lazy"
                                >
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition duration-200">
                                    <a href="{{ route('posts.show', $post->slug) }}" class="hover:no-underline">
                                        {{ $post->title }}
                                    </a>
                                </h4>
                                <div class="flex items-center space-x-2 text-xs text-gray-500 mt-1">
                                    <time datetime="{{ $post->published_at->toISOString() }}">
                                        {{ $post->published_at->format('M d') }}
                                    </time>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </div>
                @endif
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

/* Highlight search terms */
.highlight {
    background-color: #ffeb3b;
    padding: 0.1em 0.2em;
    border-radius: 0.2em;
    font-weight: 600;
}
</style>
@endpush

@push('scripts')
<script>
// Focus on search input when page loads
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput) {
        searchInput.focus();
        // Select all text for easy replacement
        searchInput.select();
    }
});
</script>
@endpush