@forelse($posts as $post)
<tr class="hover:bg-gray-50">
    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
        @if($post->featured_image)
            <img src="{{ Storage::url($post->featured_image) }}" 
                 alt="{{ $post->title }}" 
                 class="w-12 h-10 object-cover rounded-lg">
        @else
            <div class="w-12 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                <i class="fas fa-image text-gray-400 text-sm"></i>
            </div>
        @endif
    </td>
    <td class="px-4 md:px-6 py-4">
        <div class="text-sm font-medium text-gray-900">{{ Str::limit($post->title, 50) }}</div>
        <div class="text-sm text-gray-500">{{ Str::limit($post->slug, 30) }}</div>
    </td>
    <td class="px-4 md:px-6 py-4 whitespace-nowrap hidden md:table-cell">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            {{ $post->category->name ?? 'Uncategorized' }}
        </span>
    </td>
    <td class="px-4 md:px-6 py-4 whitespace-nowrap">
        @php
            $statusColors = [
                'published' => 'bg-green-100 text-green-800',
                'draft' => 'bg-yellow-100 text-yellow-800',
                'archived' => 'bg-gray-100 text-gray-800'
            ];
            $badgeClass = $statusColors[$post->status] ?? 'bg-gray-100 text-gray-800';
        @endphp
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
            {{ ucfirst($post->status) }}
        </span>
    </td>
    <td class="px-4 md:px-6 py-4 whitespace-nowrap hidden lg:table-cell">
        @if($post->is_featured)
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                <i class="fas fa-star mr-1"></i> Featured
            </span>
        @else
            <span class="text-gray-400">-</span>
        @endif
    </td>
    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
        {{ $post->views }}
    </td>
    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
        {{ $post->created_at->format('M d, Y') }}
    </td>
    <td class="px-4 md:px-6 py-4 whitespace-nowrap text-sm font-medium">
        <div class="flex items-center space-x-2">
            <a href="{{ route('posts.show', $post->slug) }}" 
               class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
               target="_blank" 
               title="View">
                <i class="fas fa-eye"></i>
            </a>
            <button class="text-green-600 hover:text-green-900 transition-colors duration-200 edit-post" 
                    data-id="{{ $post->id }}" 
                    title="Edit">
                <i class="fas fa-edit"></i>
            </button>
            <button class="text-red-600 hover:text-red-900 transition-colors duration-200 delete-post" 
                    data-id="{{ $post->id }}" 
                    title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="px-4 md:px-6 py-12 text-center">
        <div class="text-gray-400 mb-4">
            <i class="fas fa-inbox text-4xl"></i>
        </div>
        <p class="text-gray-500 mb-4">No posts found.</p>
        <button id="create-post-empty-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
            Create Your First Post
        </button>
    </td>
</tr>
@endforelse

<script>
// Handle create post button in empty state
document.getElementById('create-post-empty-btn')?.addEventListener('click', function() {
    document.getElementById('create-post-btn').click();
});
</script>