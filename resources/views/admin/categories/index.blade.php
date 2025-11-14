@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manage Categories</h1>
                <p class="text-gray-600 mt-1">Create and manage blog categories</p>
            </div>
            <div class="flex space-x-3">
                <button id="create-multiple-categories-btn" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Multiple
                </button>
                <button id="create-category-btn" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New Category
                </button>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div id="bulk-actions" class="hidden mb-4 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <span id="selected-count" class="text-sm font-medium text-gray-700">0 categories selected</span>
                </div>
                <div class="flex space-x-2">
                    <button id="bulk-delete-btn" class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 bg-red-50 rounded hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Selected
                    </button>
                    <button id="clear-selection-btn" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-50 rounded hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Clear Selection
                    </button>
                </div>
            </div>
        </div>

        <!-- Categories Table Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">All Categories</h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="categories-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="w-4 px-6 py-3">
                                    <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posts</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Data will be populated by DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Category Modal -->
<div id="category-modal" class="fixed inset-0 overflow-y-auto" style="display: none; z-index: 50;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Create Category
                    </h3>
                    <div class="mt-4" id="modal-body">
                        <!-- Form will be loaded here via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Multiple Categories Modal -->
<div id="multiple-categories-modal" class="fixed inset-0 overflow-y-auto" style="display: none; z-index: 50;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Add Multiple Categories
                    </h3>
                    <div class="mt-4">
                        <form id="multiple-categories-form" action="{{ route('admin.categories.store-multiple') }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Categories <span class="text-red-500">*</span>
                                    </label>
                                    <p class="text-sm text-gray-500 mb-3">Enter one category per line in the format: Category Name|Description (optional)</p>
                                    <textarea 
                                        id="categories-input" 
                                        name="categories_input" 
                                        rows="8" 
                                        placeholder="Technology|All about technology and innovation&#10;Lifestyle|Daily life and personal development&#10;Travel|Travel guides and experiences"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"></textarea>
                                    <p class="mt-1 text-sm text-gray-500">Example: <code>Category Name|Description</code> or just <code>Category Name</code></p>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="bulk-is-active" 
                                           name="is_active" 
                                           value="1" 
                                           checked
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="bulk-is-active" class="ml-2 block text-sm text-gray-700">
                                        Make all categories active
                                    </label>
                                </div>
                                
                                <div id="categories-preview" class="hidden mt-4 p-3 bg-gray-50 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Preview:</h4>
                                    <div id="preview-content" class="text-sm text-gray-600"></div>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-3 pt-4 mt-6">
                                <button type="button" 
                                        onclick="hideMultipleModal()"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Create Categories
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwindcss.min.css">
<style>
    .dataTables_wrapper .dataTables_length select {
        background-position: right 0.5rem center;
        padding-right: 2.5rem;
    }
    .category-row.selected {
        background-color: #f3f4f6;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwindcss.min.js"></script>
<script>
$(document).ready(function() {
    let selectedCategories = new Set();
    
    // Initialize DataTable with Tailwind styling
    var table = $('#categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.categories.index') }}",
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'px-6 py-4 whitespace-nowrap',
                render: function(data, type, row) {
                    return '<input type="checkbox" class="row-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" value="' + row.id + '">';
                }
            },
            { data: 'id', name: 'id', className: 'px-6 py-4 whitespace-nowrap text-sm text-gray-900' },
            { data: 'name', name: 'name', className: 'px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900' },
            { data: 'slug', name: 'slug', className: 'px-6 py-4 whitespace-nowrap text-sm text-gray-500' },
            { 
                data: 'description', 
                name: 'description', 
                className: 'px-6 py-4 text-sm text-gray-500',
                render: function(data, type, row) {
                    return data || '<span class="text-gray-400">No description</span>';
                }
            },
            { data: 'status_badge', name: 'is_active', orderable: false, searchable: false, className: 'px-6 py-4 whitespace-nowrap text-sm' },
            { data: 'posts_count_badge', name: 'posts_count', orderable: false, searchable: false, className: 'px-6 py-4 whitespace-nowrap text-sm' },
            { data: 'created_at', name: 'created_at', className: 'px-6 py-4 whitespace-nowrap text-sm text-gray-500' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'px-6 py-4 whitespace-nowrap text-sm font-medium' }
        ],
        order: [[1, 'desc']],
        language: {
            emptyTable: "No categories found.",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            lengthMenu: "Show _MENU_ entries",
            loadingRecords: "Loading...",
            processing: "Processing...",
            search: "Search:",
            zeroRecords: "No matching records found"
        },
        responsive: true,
        drawCallback: function() {
            // Update checkboxes after table redraw
            $('.row-checkbox').each(function() {
                $(this).prop('checked', selectedCategories.has($(this).val()));
            });
            updateBulkActions();
        }
    });

    // Row selection functionality
    $(document).on('change', '.row-checkbox', function() {
        const categoryId = $(this).val();
        if ($(this).is(':checked')) {
            selectedCategories.add(categoryId);
        } else {
            selectedCategories.delete(categoryId);
        }
        updateBulkActions();
    });

    // Select all functionality
    $('#select-all').change(function() {
        const isChecked = $(this).is(':checked');
        $('.row-checkbox').prop('checked', isChecked);
        
        if (isChecked) {
            // Get all category IDs from current page
            table.rows({ page: 'current' }).every(function() {
                const data = this.data();
                selectedCategories.add(data.id);
            });
        } else {
            selectedCategories.clear();
        }
        updateBulkActions();
    });

    function updateBulkActions() {
        const count = selectedCategories.size;
        $('#selected-count').text(count + ' categories selected');
        
        if (count > 0) {
            $('#bulk-actions').removeClass('hidden');
        } else {
            $('#bulk-actions').addClass('hidden');
        }
    }

    $('#clear-selection-btn').click(function() {
        selectedCategories.clear();
        $('.row-checkbox').prop('checked', false);
        $('#select-all').prop('checked', false);
        updateBulkActions();
    });

    // Bulk delete
    $('#bulk-delete-btn').click(function() {
        if (selectedCategories.size === 0) return;
        
        if (confirm('Are you sure you want to delete ' + selectedCategories.size + ' categories? This action cannot be undone.')) {
            $.ajax({
                url: "{{ route('admin.categories.bulk-delete') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    ids: Array.from(selectedCategories)
                },
                success: function(response) {
                    if (response.success) {
                        selectedCategories.clear();
                        table.ajax.reload();
                        showAlert('success', response.message);
                        if (response.warnings) {
                            response.warnings.forEach(warning => {
                                showAlert('warning', warning);
                            });
                        }
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Error deleting categories. Please try again.');
                }
            });
        }
    });

    // Modal functionality (exposed globally for inline onclick handlers)
    window.showModal = function() {
        document.getElementById('category-modal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    };

    window.hideModal = function() {
        document.getElementById('category-modal').style.display = 'none';
        document.body.style.overflow = 'auto';
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    };

    window.showMultipleModal = function() {
        document.getElementById('multiple-categories-modal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    };

    window.hideMultipleModal = function() {
        document.getElementById('multiple-categories-modal').style.display = 'none';
        document.body.style.overflow = 'auto';
    };

    // Close modals when clicking on background
    document.getElementById('category-modal').addEventListener('click', function(e) {
        if (e.target === this) hideModal();
    });
    document.getElementById('multiple-categories-modal').addEventListener('click', function(e) {
        if (e.target === this) hideMultipleModal();
    });

    // Create Category
    $('#create-category-btn').click(function() {
        $.ajax({
            url: "{{ route('admin.categories.create') }}",
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#modal-title').text('Create Category');
                    $('#modal-body').html(response.html);
                    showModal();
                }
            },
            error: function(xhr) {
                showAlert('error', 'Error loading form. Please try again.');
            }
        });
    });

    // Create Multiple Categories
    $('#create-multiple-categories-btn').click(function() {
        showMultipleModal();
    });

    // Preview categories input
    $('#categories-input').on('input', function() {
        const input = $(this).val().trim();
        const preview = $('#categories-preview');
        const previewContent = $('#preview-content');
        
        if (!input) {
            preview.addClass('hidden');
            return;
        }
        
        const lines = input.split('\n').filter(line => line.trim());
        let previewHtml = '<div class="space-y-1">';
        
        lines.forEach((line, index) => {
            const parts = line.split('|');
            const name = parts[0].trim();
            const description = parts[1] ? parts[1].trim() : 'No description';
            
            previewHtml += `
                <div class="flex justify-between items-center py-1 px-2 bg-white rounded">
                    <div>
                        <span class="font-medium">${name}</span>
                        <span class="text-gray-500 text-xs ml-2">${description}</span>
                    </div>
                </div>
            `;
        });
        
        previewHtml += '</div>';
        previewContent.html(previewHtml);
        preview.removeClass('hidden');
    });

    // Multiple categories form submission
    $('#multiple-categories-form').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        const textarea = $('#categories-input');
        const input = textarea.val().trim();
        
        if (!input) {
            showAlert('error', 'Please enter at least one category.');
            return;
        }
        
        const lines = input.split('\n').filter(line => line.trim());
        const categories = [];
        
        lines.forEach((line, index) => {
            const parts = line.split('|');
            const name = parts[0].trim();
            
            if (name) {
                categories.push({
                    name: name,
                    description: parts[1] ? parts[1].trim() : null
                });
            }
        });
        
        if (categories.length === 0) {
            showAlert('error', 'No valid categories found. Please check your input.');
            return;
        }
        
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html(`
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Creating ${categories.length} categories...
        `);
        
            $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                categories: categories,
                is_active: $('#bulk-is-active').is(':checked') ? 1 : 0
            },
            success: function(response) {
                submitBtn.prop('disabled', false).html(originalText);
                if (response.success) {
                    hideMultipleModal();
                    table.ajax.reload();
                    textarea.val('');
                    $('#categories-preview').addClass('hidden');
                    showAlert('success', response.message);
                    
                    if (response.warnings) {
                        response.warnings.forEach(warning => {
                            showAlert('warning', warning);
                        });
                    }
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        Object.keys(errors).forEach(function(key) {
                            showAlert('error', errors[key][0]);
                        });
                    }
                } else {
                    showAlert('error', 'An error occurred. Please try again.');
                }
            }
        });
    });

    // Edit Category
    $(document).on('click', '.edit-category', function() {
        var categoryId = $(this).data('id');
        $.ajax({
            url: "{{ url('admin/categories') }}/" + categoryId + "/edit",
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#modal-title').text('Edit Category');
                    $('#modal-body').html(response.html);
                    showModal();
                }
            },
            error: function(xhr) {
                showAlert('error', 'Error loading form. Please try again.');
            }
        });
    });

    // Save Category (Create/Update)
    $(document).on('submit', '#category-form', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method');
        
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html(`
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `);
        
        $.ajax({
            url: url,
            type: method,
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    hideModal();
                    table.ajax.reload();
                    showAlert('success', response.message);
                }
                submitBtn.prop('disabled', false).html(originalText);
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        Object.keys(errors).forEach(function(key) {
                            var input = form.find('[name="' + key + '"]');
                            input.addClass('border-red-500');
                            input.after('<div class="text-red-500 text-sm mt-1">' + errors[key][0] + '</div>');
                        });
                    }
                } else {
                    showAlert('error', 'An error occurred. Please try again.');
                }
            }
        });
    });

    // Delete Category
    $(document).on('click', '.delete-category', function() {
        var categoryId = $(this).data('id');
        var button = $(this);
        
        if (button.prop('disabled')) {
            showAlert('error', 'Cannot delete category with associated posts.');
            return;
        }
        
        if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
            $.ajax({
                url: "{{ url('admin/categories') }}/" + categoryId,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        table.ajax.reload();
                        showAlert('success', response.message);
                    } else {
                        showAlert('error', response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('error', 'Error deleting category. Please try again.');
                }
            });
        }
    });

    function showAlert(type, message) {
        const alertClass = {
            'success': 'bg-green-500',
            'error': 'bg-red-500',
            'warning': 'bg-yellow-500'
        }[type] || 'bg-blue-500';
        
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 p-4 rounded-md text-white ${alertClass} z-50`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 5000);
    }
});
</script>
@endpush