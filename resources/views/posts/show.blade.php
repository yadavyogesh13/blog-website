@extends('layouts.app')

@section('title', $post->title)

@section('meta')
    @if($post->seo)
        <meta name="description" content="{{ $post->seo->meta_description }}">
        <meta name="keywords" content="{{ $post->seo->meta_keywords }}">
        <meta property="og:title" content="{{ $post->seo->og_title ?? $post->title }}">
        <meta property="og:description" content="{{ $post->seo->og_description ?? $post->excerpt }}">
        <meta property="og:image" content="{{ $post->seo->og_image ? Storage::url($post->seo->og_image) : ($post->featured_image ? Storage::url($post->featured_image) : '') }}">
    @else
        <meta name="description" content="{{ $post->excerpt }}">
        <meta property="og:title" content="{{ $post->title }}">
        <meta property="og:description" content="{{ $post->excerpt }}">
        @if($post->featured_image)
        <meta property="og:image" content="{{ Storage::url($post->featured_image) }}">
        @endif
    @endif
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <!-- JSON-LD Structured Data -->
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BlogPosting",
        "headline": "{{ $post->title }}",
        "description": "{{ $post->excerpt }}",
        "image": "{{ $post->featured_image ? Storage::url($post->featured_image) : '' }}",
        "author": {
            "@type": "Person",
            "name": "{{ $post->user->name }}"
        },
        "publisher": {
            "@type": "Organization",
            "name": "BlogSite",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ url('/logo.png') }}"
            }
        },
        "datePublished": "{{ $post->published_at->toIso8601String() }}",
        "dateModified": "{{ $post->updated_at->toIso8601String() }}"
    }
    </script>
    @endverbatim
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article>
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('categories.show', $post->category->slug) }}">{{ $post->category->name }}</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($post->title, 30) }}</li>
                    </ol>
                </nav>

                <!-- Post Header -->
                <header class="mb-4">
                    <h1 class="display-5 fw-bold">{{ $post->title }}</h1>
                    <div class="d-flex flex-wrap align-items-center text-muted mb-3">
                        <span class="me-3">
                            <i class="fas fa-user me-1"></i>
                            By {{ $post->user->name }}
                        </span>
                        <span class="me-3">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $post->published_at->format('F d, Y') }}
                        </span>
                        <span class="me-3">
                            <i class="fas fa-eye me-1"></i>
                            {{ $post->views }} views
                        </span>
                        <span class="badge bg-primary">{{ $post->category->name }}</span>
                    </div>
                </header>

                <!-- Featured Image -->
                @if($post->featured_image)
                <div class="mb-4">
                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid rounded" style="max-height: 400px; width: 100%; object-fit: cover;">
                </div>
                @endif

                <!-- Post Content -->
                <div class="post-content mb-5">
                    {!! $post->content !!}
                </div>

                <!-- Social Sharing -->
                <div class="card mb-5">
                    <div class="card-body">
                        <h6 class="card-title">Share this post:</h6>
                        <div class="social-sharing">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                               target="_blank" class="btn btn-outline-primary btn-sm me-2">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" 
                               target="_blank" class="btn btn-outline-info btn-sm me-2">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
                               target="_blank" class="btn btn-outline-secondary btn-sm">
                                <i class="fab fa-linkedin-in"></i> LinkedIn
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related Posts -->
            @if($related_posts->count() > 0)
            <section class="mb-5">
                <h3 class="mb-4">Related Posts</h3>
                <div class="row">
                    @foreach($related_posts as $related_post)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            @if($related_post->featured_image)
                            <img src="{{ Storage::url($related_post->featured_image) }}" class="card-img-top" alt="{{ $related_post->title }}" style="height: 120px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">{{ Str::limit($related_post->title, 40) }}</h6>
                                <a href="{{ route('posts.show', $related_post->slug) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Recent Posts -->
            <div class="sidebar-widget">
                <h4>Recent Posts</h4>
                @foreach($recent_posts as $recent_post)
                <div class="mb-3">
                    <h6><a href="{{ route('posts.show', $recent_post->slug) }}" class="text-decoration-none">{{ Str::limit($recent_post->title, 40) }}</a></h6>
                    <small class="text-muted">{{ $recent_post->published_at->format('M d, Y') }}</small>
                </div>
                @endforeach
            </div>

            <!-- Categories -->
            <div class="sidebar-widget">
                <h4>Categories</h4>
                @foreach($categories as $category)
                <div class="mb-2">
                    <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                        {{ $category->name }} ({{ $category->posts_count }})
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection