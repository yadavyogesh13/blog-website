<form id="category-form" action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
    @csrf
    
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
            Category Name <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               id="name" 
               name="name" 
               value="{{ old('name') }}"
               required 
               placeholder="Enter category name"
               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">
        <p class="mt-1 text-sm text-gray-500">Category name must be unique and descriptive.</p>
    </div>
    
    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
            Description
        </label>
        <textarea id="description" 
                  name="description" 
                  rows="3" 
                  placeholder="Enter category description (optional)"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150">{{ old('description') }}</textarea>
        <p class="mt-1 text-sm text-gray-500">Maximum 500 characters. This helps with SEO and user understanding.</p>
    </div>
    
    <div class="flex items-center">
        <input type="checkbox" 
               id="is_active" 
               name="is_active" 
               value="1" 
               checked
               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
        <label for="is_active" class="ml-2 block text-sm text-gray-700">
            Active Category
        </label>
    </div>
    <p class="text-sm text-gray-500 -mt-2">Inactive categories won't be visible on the frontend.</p>
    
    <div class="flex justify-end space-x-3 pt-4">
        <button type="button" 
                onclick="hideModal()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
            Cancel
        </button>
        <button type="submit" 
                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Create Category
        </button>
    </div>
</form>

<script>
function hideModal() {
    document.getElementById('category-modal').style.display = 'none';
    document.body.style.overflow = 'auto';
    $('.border-red-500').removeClass('border-red-500');
    $('.text-red-500.text-sm').remove();
}
</script>