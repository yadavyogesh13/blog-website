@extends('layouts.admin')

@section('title', 'Manage Posts')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Manage Posts</h1>
            <p class="text-gray-600 mt-1">Create and manage your blog posts</p>
        </div>
        <button id="create-post-btn" class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium flex items-center transition-colors duration-200 shadow-sm hover:shadow-md">
            <i class="fas fa-plus mr-2"></i>
            Create New Post
        </button>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="p-4 md:p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Filters & Search</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <option value="">All Status</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category-filter" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select id="category-filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search-input" class="block text-sm font-medium text-gray-700 mb-2">Search Posts</label>
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="Search by title, excerpt, or category..." 
                               class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 md:px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">All Posts</h2>
            <div id="loading-spinner" class="hidden">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="posts-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Category</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Featured</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Views</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Date</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create/Edit Post Modal -->
<div id="post-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-6xl w-full max-h-[95vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 id="post-modal-title" class="text-xl font-semibold text-gray-900">Create Post</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors duration-200" onclick="closeModal()">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(95vh-120px)]" id="post-modal-body">
            <!-- Form will be loaded here via AJAX -->
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-post-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Confirm Delete</h3>
            <p class="text-gray-600 text-center mb-6">Are you sure you want to delete this post? This action cannot be undone.</p>
            <div class="flex space-x-3">
                <button type="button" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200" onclick="closeDeleteModal()">
                    Cancel
                </button>
                <button type="button" id="confirm-delete-post" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwindcss.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
<style>
    /* DataTables Pagination Styling */
    .dataTables_wrapper .dataTables_paginate {
        @apply flex items-center space-x-1;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        @apply px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200 mx-0.5;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700 hover:text-white;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        @apply opacity-50 cursor-not-allowed hover:bg-white hover:text-gray-700;
    }
    
    /* DataTables Info Styling */
    .dataTables_wrapper .dataTables_info {
        @apply text-sm text-gray-600;
    }
    
    /* DataTables Length Styling */
    .dataTables_wrapper .dataTables_length {
        @apply mb-4;
    }
    
    .dataTables_wrapper .dataTables_length select {
        @apply px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200;
        background-position: right 0.5rem center;
        padding-right: 2.5rem;
    }
    
    .dataTables_wrapper .dataTables_length label {
        @apply text-sm text-gray-700;
    }
    
    /* DataTables Filter Styling */
    .dataTables_wrapper .dataTables_filter {
        @apply mb-4;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        @apply px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200;
    }
    
    .dataTables_wrapper .dataTables_filter label {
        @apply text-sm text-gray-700;
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .dataTables_wrapper .dataTables_paginate {
            @apply justify-center;
        }
        
        .dataTables_wrapper .dataTables_info {
            @apply text-center mb-2;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script>
// Global variables
let deletePostId = null;
let editor = null;
let postsTable = null;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeDataTable();
    setupEventListeners();
});

function initializeDataTable() {
    postsTable = $('#posts-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.posts.index') }}",
            data: function (d) {
                d.status = $('#status-filter').val();
                d.category = $('#category-filter').val();
                d.search = $('#search-input').val();
            }
        },
        columns: [
            { data: 'image', name: 'featured_image', orderable: false, searchable: false, className: 'px-4 md:px-6 py-4 whitespace-nowrap' },
            { data: 'title_column', name: 'title', orderable: true, className: 'px-4 md:px-6 py-4' },
            { data: 'category_badge', name: 'category.name', orderable: false, className: 'px-4 md:px-6 py-4 whitespace-nowrap hidden md:table-cell' },
            { data: 'status_badge', name: 'status', orderable: true, className: 'px-4 md:px-6 py-4 whitespace-nowrap' },
            { data: 'featured_badge', name: 'is_featured', orderable: true, className: 'px-4 md:px-6 py-4 whitespace-nowrap hidden lg:table-cell' },
            { data: 'views', name: 'views', orderable: true, className: 'px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell' },
            { data: 'created_at', name: 'created_at', orderable: true, className: 'px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium' }
        ],
        order: [[6, 'desc']], // Sort by created_at desc by default
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            emptyTable: "No posts found.",
            info: "Showing _START_ to _END_ of _TOTAL_ posts",
            infoEmpty: "Showing 0 to 0 of 0 posts",
            infoFiltered: "(filtered from _MAX_ total posts)",
            lengthMenu: "Show _MENU_ posts",
            loadingRecords: "Loading...",
            processing: '<div class="flex justify-center"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div></div>',
            search: "Search:",
            zeroRecords: "No matching posts found",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        responsive: true,
        dom: '<"flex flex-col md:flex-row md:items-center justify-between"<"mb-4"l><"mb-4 md:mb-0"f>>rt<"flex flex-col md:flex-row md:items-center justify-between mt-4"<"mb-4 md:mb-0"i><"mb-4 md:mb-0"p>>',
        initComplete: function() {
            // Add custom search input
            $('#search-input').on('keyup', function() {
                postsTable.search(this.value).draw();
            });
        },
        drawCallback: function() {
            // Custom styling for pagination
            $('.dataTables_paginate').addClass('flex items-center space-x-1');
            $('.paginate_button').addClass('px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors duration-200');
            $('.paginate_button.current').addClass('bg-blue-600 text-white border-blue-600 hover:bg-blue-700');
            $('.paginate_button.disabled').addClass('opacity-50 cursor-not-allowed hover:bg-transparent');
        }
    });
}

function setupEventListeners() {
    // Filter handlers with debouncing
    let searchTimeout;
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            postsTable.draw();
        }, 500);
    });

    $('#status-filter, #category-filter').on('change', function() {
        postsTable.draw();
    });

    // Create post button
    $('#create-post-btn').on('click', loadPostForm);

    // Edit and delete post event delegation
    $(document).on('click', '.edit-post', function(e) {
        e.preventDefault();
        const postId = $(this).data('id');
        console.log('Editing post ID:', postId); // Debug log
        if (postId) {
            loadPostForm(postId);
        } else {
            console.error('No post ID found');
            showToast('error', 'Error: No post ID found');
        }
    });

    $(document).on('click', '.delete-post', function(e) {
        e.preventDefault();
        const postId = $(this).data('id');
        console.log('Deleting post ID:', postId); // Debug log
        if (postId) {
            deletePostId = postId;
            $('#delete-post-modal').removeClass('hidden');
        } else {
            console.error('No post ID found for deletion');
            showToast('error', 'Error: No post ID found');
        }
    });

    // Confirm delete
    $('#confirm-delete-post').on('click', confirmDeletePost);

    // Save post form
    $(document).on('submit', '#post-form', handlePostFormSubmit);
}

function loadPostForm(postId = null) {
    let url, title;
    
    if (postId && !isNaN(postId)) {
        url = `/admin/posts/${postId}/edit`;
        title = 'Edit Post';
    } else {
        url = '{{ route("admin.posts.create") }}';
        title = 'Create Post';
    }
    
    console.log('Loading form from URL:', url); // Debug log
    
    $('#post-modal-title').text(title);
    $('#post-modal-body').html(`
        <div class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600">Loading form...</span>
        </div>
    `);
    $('#post-modal').removeClass('hidden');

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json' // Change this to expect JSON
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json(); // Change this to parse JSON instead of text
    })
    .then(data => {
        // Check if response has success and html properties
        if (data.success && data.html) {
            $('#post-modal-body').html(data.html);
            initializeSeoAutoFill();
            setTimeout(initializeCKEditor, 300);
        } else {
            throw new Error('Invalid response format');
        }
    })
    .catch(error => {
        console.error('Error loading form:', error);
        $('#post-modal-body').html(`
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                <p class="text-red-600 mb-4">Error loading form. Please try again.</p>
                <p class="text-sm text-gray-500 mb-4">Error: ${error.message}</p>
                <button onclick="loadPostForm(${postId || ''})" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Retry
                </button>
            </div>
        `);
    });
}

function initializeSeoAutoFill() {
    const titleField = $('#title');
    const metaTitleField = $('#meta_title');
    const excerptField = $('#excerpt');
    const metaDescField = $('#meta_description');

    titleField.on('input', function() {
        if (!metaTitleField.val()) {
            metaTitleField.val(this.value);
        }
    });

    excerptField.on('input', function() {
        if (!metaDescField.val()) {
            metaDescField.val(this.value.substring(0, 160));
        }
    });
}

function initializeCKEditor() {
    const contentElement = document.getElementById('content');
    if (!contentElement) return;

    // Destroy existing editor
    if (editor) {
        editor.destroy().catch(console.warn);
    }

    // Hide loading and show textarea
    const editorLoading = document.getElementById('editor-loading');
    if (editorLoading) {
        editorLoading.style.display = 'none';
    }
    contentElement.classList.remove('hidden');

    if (typeof ClassicEditor !== 'undefined') {
        ClassicEditor
            .create(contentElement, {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', '|',
                        'link', 'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'blockQuote', 'insertTable', 'imageUpload', '|',
                        'undo', 'redo', '|',
                        'codeBlock', 'htmlEmbed'
                    ]
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4' }
                    ]
                },
                language: 'en',
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side',
                        'linkImage'
                    ]
                },
                simpleUpload: {
                    uploadUrl: '{{ route("admin.upload") }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                licenseKey: '',
            })
            .then(newEditor => {
                editor = newEditor;
                console.log('CKEditor initialized successfully');
                
                // Handle editor errors
                editor.model.document.on('change:data', () => {
                    // Sync content back to textarea for form submission
                    document.getElementById('content').value = editor.getData();
                });
            })
            .catch(error => {
                console.error('Error initializing CKEditor:', error);
                
                // Fallback: show textarea and hide loading
                if (editorLoading) {
                    editorLoading.style.display = 'none';
                }
                contentElement.classList.remove('hidden');
                
                showToast('warning', 'Editor initialization failed. Using basic textarea.');
            });
    } else {
        console.error('CKEditor library not loaded');
        if (editorLoading) {
            editorLoading.style.display = 'none';
        }
        contentElement.classList.remove('hidden');
        showToast('error', 'Editor library not loaded. Using basic textarea.');
    }
}

function handlePostFormSubmit(e) {
    e.preventDefault();
    
    // Get CKEditor content
    if (editor) {
        document.getElementById('content').value = editor.getData();
    }
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';

    fetch(form.action, {
        method: form.method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        if (data.success) {
            showToast('success', data.message);
            closeModal();
            postsTable.draw();
        } else {
            showValidationErrors(data.errors);
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        console.error('Error:', error);
        showToast('error', 'Error saving post. Please try again.');
    });
}

function confirmDeletePost() {
    if (!deletePostId) return;

    fetch(`/admin/posts/${deletePostId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            closeDeleteModal();
            postsTable.draw();
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'Error deleting post');
    });
}

function showValidationErrors(errors) {
    // Clear previous errors
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();

    if (errors) {
        Object.keys(errors).forEach(field => {
            const input = $(`[name="${field}"]`);
            input.addClass('is-invalid');
            input.after(`<div class="invalid-feedback text-red-600 text-sm mt-1">${errors[field][0]}</div>`);
        });
        
        // Scroll to first error
        $('.is-invalid').first().get(0)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

// Global functions
function closeModal() {
    $('#post-modal').addClass('hidden');
    if (editor) {
        editor.destroy().then(() => {
            editor = null;
        }).catch(console.warn);
    }
}

function closeDeleteModal() {
    $('#delete-post-modal').addClass('hidden');
    deletePostId = null;
}

function showToast(type, message) {
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-red-500';
    const icon = type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle';
    
    const toast = $(`
        <div class="${bgColor} text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-between max-w-sm transform transition-transform duration-300 translate-x-full">
            <div class="flex items-center">
                <i class="fas ${icon} mr-3"></i>
                <span>${message}</span>
            </div>
            <button class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `);
    
    $('#toast-container').append(toast);
    
    // Animate in
    setTimeout(() => toast.removeClass('translate-x-full'), 10);
    
    // Remove on click
    toast.find('button').on('click', () => removeToast(toast));
    
    // Auto remove
    setTimeout(() => removeToast(toast), 5000);
}

function removeToast(toast) {
    toast.addClass('translate-x-full');
    setTimeout(() => toast.remove(), 300);
}
</script>
@endpush