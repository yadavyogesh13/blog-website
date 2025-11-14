@extends('layouts.admin')

@section('title', 'Create New Post')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New Post</h1>
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Posts
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-edit me-1"></i> Post Content</h6>
                </div>
                <div class="card-body">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Post Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" 
                               placeholder="Enter post title" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Excerpt -->
                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Excerpt *</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                  id="excerpt" name="excerpt" rows="3" 
                                  placeholder="Brief description of the post" required>{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">A short summary of your post (max 500 characters).</div>
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Content *</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="12" 
                                  placeholder="Write your post content here...">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SEO Section -->
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-search me-1"></i> SEO Settings</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                               id="meta_title" name="meta_title" value="{{ old('meta_title') }}" 
                               placeholder="Meta title for SEO">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Recommended: 50-60 characters</div>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" name="meta_description" rows="3" 
                                  placeholder="Meta description for SEO">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Recommended: 150-160 characters</div>
                    </div>

                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                               id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" 
                               placeholder="keyword1, keyword2, keyword3">
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Separate keywords with commas</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Publish Settings -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-cog me-1"></i> Publish Settings</h6>
                </div>
                <div class="card-body">
                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Featured Image -->
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">Featured Image</label>
                        <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                               id="featured_image" name="featured_image" 
                               accept="image/*">
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Recommended size: 1200x630px, Max: 2MB</div>
                    </div>

                    <!-- Featured Post -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   id="is_featured" name="is_featured" value="1" 
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Mark as Featured Post
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-1"></i> Create Post
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
    .card-header {
        border-bottom: none;
    }
</style>
@endpush

@push('scripts')
<script>
    // Character counters
    document.getElementById('title').addEventListener('input', function() {
        const metaTitle = document.getElementById('meta_title');
        if (!metaTitle.value) {
            metaTitle.value = this.value;
        }
    });

    document.getElementById('excerpt').addEventListener('input', function() {
        const metaDesc = document.getElementById('meta_description');
        if (!metaDesc.value) {
            metaDesc.value = this.value.substring(0, 160);
        }
    });
</script>

<script>
    // Handle content validation when using a rich editor that hides the textarea
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            const textarea = document.getElementById('content');
            if (!textarea) return;

            // If a CKEditor instance named `editor` exists, use it
            let contentValue = textarea.value;
            try {
                if (window.editor && typeof window.editor.getData === 'function') {
                    contentValue = window.editor.getData();
                } else if (typeof ClassicEditor !== 'undefined' && textarea.classList.contains('ck-content')) {
                    // nothing special, fallback to value
                }
            } catch (err) {
                console.warn('Could not read editor data:', err);
            }

            // Sync editor data back to the textarea so server receives it
            textarea.value = contentValue;

            // If the textarea is hidden and required, remove native required to avoid "not focusable" error
            const isHidden = (textarea.offsetParent === null) || (getComputedStyle(textarea).display === 'none');
            if (textarea.required && isHidden) {
                textarea.required = false;
            }

            // Simple empty content check (strip tags)
            const plain = contentValue.replace(/<[^>]*>/g, '').trim();
            if (!plain) {
                // Prevent submit and show a custom validation message
                e.preventDefault();

                let feedback = textarea.parentNode.querySelector('.invalid-feedback.editor-error');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback editor-error';
                    textarea.parentNode.appendChild(feedback);
                }
                feedback.textContent = 'Content is required.';
                textarea.classList.add('is-invalid');

                // Scroll to editor or textarea for visibility
                const editorEl = document.querySelector('.ck-editor') || textarea;
                try { editorEl.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (err) {}
                return false;
            }

            // If content present, ensure any previous error state is cleared
            textarea.classList.remove('is-invalid');
            const old = textarea.parentNode.querySelector('.invalid-feedback.editor-error');
            if (old) old.remove();
        });
    });
</script>
@endpush