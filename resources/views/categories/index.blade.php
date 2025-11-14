@extends('layouts.app')

@section('title', $seo['title'])

@section('meta')
    <meta name="description" content="{{ $seo['description'] }}">
    <meta name="keywords" content="{{ $seo['keywords'] }}">
    <link rel="canonical" href="{{ $seo['canonical'] }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="{{ $seo['title'] }}">
    <meta property="twitter:description" content="{{ $seo['description'] }}">
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Blog Categories</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Explore our content by category. Find articles that match your interests and discover new topics.
            </p>
        </div>

        <!-- Categories Grid -->
        @if($categories->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($categories as $category)
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden group">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900 group-hover:text-blue-600 transition duration-200">
                            <a href="{{ route('categories.show', $category->slug) }}" class="hover:no-underline">
                                {{ $category->name }}
                            </a>
                        </h2>
                        <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                            {{ $category->posts_count }} posts
                        </span>
                    </div>
                    
                    @if($category->description)
                    <p class="text-gray-600 mb-4 line-clamp-3">
                        {{ $category->description }}
                    </p>
                    @endif
                    
                    <a href="{{ route('categories.show', $category->slug) }}" 
                       class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700 transition duration-200 group/link">
                        Browse Posts
                        <svg class="w-4 h-4 ml-1 transform group-hover/link:translate-x-1 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No categories available</h3>
            <p class="text-gray-500">Check back later for organized content.</p>
        </div>
        @endif

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-2xl p-8 text-center text-white">
            <h2 class="text-2xl font-bold mb-4">Can't Find What You're Looking For?</h2>
            <p class="text-blue-100 mb-6 max-w-2xl mx-auto">
                Browse all our articles or use search to find specific content.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('posts.index') }}" 
                   class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition duration-300">
                    Browse All Articles
                </a>
                <a href="{{ route('search') }}" 
                   class="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                    Search Articles
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush