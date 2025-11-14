@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="flex-1">
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">Dashboard Overview</h1>
                <p class="text-gray-600 mt-2 text-lg">Welcome back, Administrator! Here's what's happening today.</p>
            </div>
            <div class="mt-4 lg:mt-0">
                {{-- <a href="{{ route('admin.posts.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 border border-transparent rounded-xl font-semibold text-white hover:from-primary-700 hover:to-primary-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-plus mr-3"></i>
                    Create New Post
                </a> --}}
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Total Posts -->
        <div class="stats-card rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Posts</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2 group-hover:text-primary-600 transition-colors duration-200">{{ $stats['total_posts'] }}</p>
                    <div class="flex items-center mt-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-database mr-1"></i>
                            All Content
                        </span>
                    </div>
                </div>
                <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg group-hover:animate-bounce-subtle">
                    <i class="fas fa-newspaper text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Published Posts -->
        <div class="stats-card rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Published</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2 group-hover:text-green-600 transition-colors duration-200">{{ $stats['published_posts'] }}</p>
                    <div class="flex items-center mt-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>
                            Live Content
                        </span>
                    </div>
                </div>
                <div class="p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg group-hover:animate-bounce-subtle">
                    <i class="fas fa-check-circle text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="stats-card rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Categories</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2 group-hover:text-cyan-600 transition-colors duration-200">{{ $stats['total_categories'] }}</p>
                    <div class="flex items-center mt-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                            <i class="fas fa-tags mr-1"></i>
                            Organized
                        </span>
                    </div>
                </div>
                <div class="p-4 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl shadow-lg group-hover:animate-bounce-subtle">
                    <i class="fas fa-folder text-white text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Views -->
        <div class="stats-card rounded-2xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Views</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2 group-hover:text-amber-600 transition-colors duration-200">{{ $stats['total_views'] }}</p>
                    <div class="flex items-center mt-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            <i class="fas fa-chart-line mr-1"></i>
                            Engagement
                        </span>
                    </div>
                </div>
                <div class="p-4 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg group-hover:animate-bounce-subtle">
                    <i class="fas fa-eye text-white text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Recent Posts -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 sm:mb-0">Recent Posts</h3>
                    <a href="{{ route('admin.posts.index') }}" 
                       class="inline-flex items-center text-primary-600 hover:text-primary-800 font-medium transition-colors duration-200 group">
                        View All Posts
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-200"></i>
                    </a>
                </div>
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title & Category</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Views</th>
                                {{-- <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($recent_posts as $post)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0 w-10 h-10 bg-primary-50 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-file-alt text-primary-600"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 group-hover:text-primary-600 transition-colors duration-200">
                                                {{ Str::limit($post->title, 35) }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $post->category->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($post->status === 'published')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-circle text-[6px] mr-2"></i>
                                            Published
                                        </span>
                                    @elseif($post->status === 'draft')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-circle text-[6px] mr-2"></i>
                                            Draft
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            <i class="fas fa-circle text-[6px] mr-2"></i>
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $post->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center text-sm text-gray-900">
                                        <i class="fas fa-eye mr-2 text-amber-500"></i>
                                        {{ $post->views }}
                                    </div>
                                </td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('posts.show', $post->slug) }}" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-2 rounded-lg hover:bg-blue-50"
                                           title="View">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.posts.edit', $post) }}" 
                                           class="text-primary-600 hover:text-primary-900 transition-colors duration-200 p-2 rounded-lg hover:bg-primary-50"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td> --}}
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                                        <p class="text-lg font-medium">No posts yet</p>
                                        <p class="text-sm mt-1">Create your first post to get started</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="space-y-8">
            <!-- Popular Posts -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900">Trending Posts</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($popular_posts as $index => $post)
                        <div class="group p-4 rounded-xl border border-gray-100 hover:border-primary-200 hover:bg-primary-50 transition-all duration-200 cursor-pointer transform hover:scale-105">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center text-primary-600 font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 group-hover:text-primary-700 transition-colors duration-200 mb-2 line-clamp-2">
                                        {{ Str::limit($post->title, 45) }}
                                    </h4>
                                    <div class="flex justify-between items-center text-sm text-gray-500">
                                        <span class="inline-flex items-center">
                                            <i class="fas fa-folder mr-1 text-blue-500"></i>
                                            {{ $post->category->name }}
                                        </span>
                                        <span class="inline-flex items-center font-semibold text-amber-600">
                                            <i class="fas fa-fire mr-1"></i>
                                            {{ $post->views }} views
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-chart-line text-4xl mb-4 text-gray-300"></i>
                            <p class="font-medium">No trending posts</p>
                            <p class="text-sm mt-1">Posts will appear here as they get views</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Stats -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                {{-- <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-3">
                            <a href="{{ route('admin.posts.create') }}" 
                               class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-primary-50 to-primary-100 border border-primary-200 hover:from-primary-100 hover:to-primary-200 transition-all duration-200 group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-primary-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                        <i class="fas fa-plus text-white"></i>
                                    </div>
                                    <span class="font-semibold text-primary-700">New Post</span>
                                </div>
                                <i class="fas fa-chevron-right text-primary-400 group-hover:translate-x-1 transition-transform duration-200"></i>
                            </a>
                            <a href="{{ route('admin.categories.create') }}" 
                               class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-green-50 to-green-100 border border-green-200 hover:from-green-100 hover:to-green-200 transition-all duration-200 group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                                        <i class="fas fa-folder-plus text-white"></i>
                                    </div>
                                    <span class="font-semibold text-green-700">Add Category</span>
                                </div>
                                <i class="fas fa-chevron-right text-green-400 group-hover:translate-x-1 transition-transform duration-200"></i>
                            </a>
                        </div>
                    </div>
                </div> --}}

                <!-- Performance Stats -->
                {{-- <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-bold mb-6">Performance Summary</h3>
                    <div class="space-y-5">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-primary-100">Published Rate</span>
                                <span class="font-bold text-lg">
                                    {{ $stats['total_posts'] > 0 ? round(($stats['published_posts'] / $stats['total_posts']) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-primary-500 rounded-full h-2">
                                <div class="bg-green-400 h-2 rounded-full" style="width: {{ $stats['total_posts'] > 0 ? ($stats['published_posts'] / $stats['total_posts']) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-primary-100">Avg. Views per Post</span>
                                <span class="font-bold text-lg">
                                    {{ $stats['total_posts'] > 0 ? round($stats['total_views'] / $stats['total_posts'], 1) : 0 }}
                                </span>
                            </div>
                            <div class="w-full bg-primary-500 rounded-full h-2">
                                <div class="bg-amber-400 h-2 rounded-full" style="width: {{ min(($stats['total_posts'] > 0 ? ($stats['total_views'] / $stats['total_posts']) / 10 * 100 : 0), 100) }}%"></div>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-primary-500">
                            <div class="text-center">
                                <p class="text-primary-200 text-sm">🎉 Keep up the great work!</p>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add staggered animation to stats cards
        const statsCards = document.querySelectorAll('.stats-card');
        statsCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 100}ms`;
            card.classList.add('animate-fade-in');
        });

        // Add interactive effects to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(8px)';
            });
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });

        // Add click effects to quick action cards
        const quickActions = document.querySelectorAll('.bg-gradient-to-r');
        quickActions.forEach(action => {
            action.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
    });
</script>
@endsection