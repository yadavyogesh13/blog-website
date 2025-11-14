<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return $this->getCategoriesData();
        }
        
        return view('admin.categories.index');
    }

    private function getCategoriesData()
    {
        $categories = Category::select('categories.*')->withCount('posts');

        return DataTables::eloquent($categories)
            ->addColumn('status_badge', function($category) {
                $badgeClass = $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                $statusText = $category->is_active ? 'Active' : 'Inactive';
                return '<span class="px-2 py-1 text-xs font-semibold rounded-full '.$badgeClass.'">'.$statusText.'</span>';
            })
            ->addColumn('posts_count_badge', function($category) {
                return '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">'.$category->posts_count.'</span>';
            })
            ->addColumn('actions', function($category) {
                return '
                    <div class="flex space-x-2">
                        <button class="edit-category inline-flex items-center px-2 py-1 text-xs font-medium text-gray-600 bg-gray-50 rounded hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500" 
                                data-id="'.$category->id.'" 
                                title="Edit">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                        <button class="delete-category inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 bg-red-50 rounded hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500" 
                                data-id="'.$category->id.'" 
                                '.($category->posts_count > 0 ? 'disabled' : '').' 
                                title="Delete">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </div>
                ';
            })
            ->editColumn('description', function($category) {
                return $category->description ?: '<span class="text-gray-400">No description</span>';
            })
            ->editColumn('created_at', function($category) {
                return $category->created_at->format('M d, Y');
            })
            ->rawColumns(['actions', 'status_badge', 'posts_count_badge', 'description'])
            ->make(true);
    }

    public function create()
    {
        return response()->json([
            'success' => true,
            'html' => view('admin.categories.partials.create-form')->render()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
                'description' => 'nullable|string|max:500',
                'is_active' => 'nullable|boolean'
            ]);

            // Generate unique slug
            $slug = Str::slug($request->name);
            $counter = 1;
            $originalSlug = $slug;
            while (Category::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
            
            $validated['is_active'] = $request->boolean('is_active', false);

            $category = Category::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully!',
                'data' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store multiple categories at once
     */
    public function storeMultiple(Request $request)
    {
        try {
            $request->validate([
                'categories' => 'required|array|min:1',
                'categories.*.name' => 'required|string|max:255|distinct',
                'categories.*.description' => 'nullable|string|max:500',
                'is_active' => 'nullable' // will be interpreted as boolean below
            ]);

            $createdCategories = [];
            $errors = [];

            foreach ($request->categories as $index => $categoryData) {
                try {
                    // Check if category name already exists
                    if (Category::where('name', $categoryData['name'])->exists()) {
                        $errors[] = "Category '{$categoryData['name']}' already exists.";
                        continue;
                    }

                    // Generate unique slug
                    $slug = Str::slug($categoryData['name']);
                    $counter = 1;
                    $originalSlug = $slug;
                    while (Category::where('slug', $slug)->exists()) {
                        $slug = $originalSlug . '-' . $counter;
                        $counter++;
                    }

                    $category = Category::create([
                        'name' => $categoryData['name'],
                        'slug' => $slug,
                        'description' => $categoryData['description'] ?? null,
                        'is_active' => $request->boolean('is_active', false)
                    ]);

                    $createdCategories[] = $category;

                } catch (\Exception $e) {
                    $errors[] = "Error creating category '{$categoryData['name']}': " . $e->getMessage();
                }
            }

            $response = [
                'success' => true,
                'message' => count($createdCategories) . ' categories created successfully!',
                'created_count' => count($createdCategories),
                'created_categories' => $createdCategories
            ];

            if (!empty($errors)) {
                $response['warnings'] = $errors;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating categories: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Category $category)
    {
        return response()->json([
            'success' => true,
            'data' => $category,
            'html' => view('admin.categories.partials.edit-form', compact('category'))->render()
        ]);
    }

    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'description' => 'nullable|string|max:500',
                'is_active' => 'nullable|boolean'
            ]);

            // Generate unique slug (avoid collisions with other categories)
            $slug = Str::slug($request->name);
            $counter = 1;
            $originalSlug = $slug;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
            
            $validated['is_active'] = $request->boolean('is_active', false);

            $category->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully!',
                'data' => $category
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating category: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->posts()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with associated posts.'
                ], 422);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category moved to trash successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restore($id)
    {
        try {
            $category = Category::withTrashed()->findOrFail($id);
            if (!$category->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category is not in trash.'
                ], 422);
            }

            $category->restore();

            return response()->json([
                'success' => true,
                'message' => 'Category restored successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error restoring category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permanently delete a category (only allowed when no posts exist).
     */
    public function forceDelete($id)
    {
        try {
            $category = Category::withTrashed()->findOrFail($id);

            if ($category->posts()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot permanently delete category with associated posts.'
                ], 422);
            }

            // Delete SEO if exists
            $category->seo()->delete();

            $category->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Category permanently deleted.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error permanently deleting category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete categories
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:categories,id'
            ]);

            $categories = Category::whereIn('id', $request->ids)->get();
            $deletedCount = 0;
            $errors = [];

            foreach ($categories as $category) {
                if ($category->posts()->count() > 0) {
                    $errors[] = "Cannot delete category '{$category->name}' because it has associated posts.";
                    continue;
                }

                $category->delete();
                $deletedCount++;
            }

            $response = [
                'success' => true,
                'message' => "{$deletedCount} categories deleted successfully!",
                'deleted_count' => $deletedCount
            ];

            if (!empty($errors)) {
                $response['warnings'] = $errors;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error bulk deleting categories: ' . $e->getMessage()
            ], 500);
        }
    }
}