<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getPostsData($request);
        }
        
        $categories = Category::where('is_active', true)->get();
        return view('admin.posts.index', compact('categories'));
    }

    private function getPostsData(Request $request)
    {
        try {
            $query = Post::with(['category:id,name', 'user:id,name']);

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%")
                      ->orWhereHas('category', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }

            return DataTables::eloquent($query)
                ->addColumn('image', function($post) {
                    if ($post->featured_image) {
                        return '<img src="'.Storage::url($post->featured_image).'" class="w-12 h-10 object-cover rounded-lg" alt="'.$post->title.'">';
                    }
                    return '<div class="w-12 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-sm"></i>
                    </div>';
                })
                ->addColumn('title_column', function($post) {
                    return '<div class="text-sm font-medium text-gray-900">'.Str::limit($post->title, 50).'</div>
                           <div class="text-sm text-gray-500">'.Str::limit($post->slug, 30).'</div>';
                })
                ->addColumn('category_badge', function($post) {
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        '.($post->category->name ?? 'Uncategorized').'
                    </span>';
                })
                ->addColumn('status_badge', function($post) {
                    $statusColors = [
                        'published' => 'bg-green-100 text-green-800',
                        'draft' => 'bg-yellow-100 text-yellow-800',
                        'archived' => 'bg-gray-100 text-gray-800'
                    ];
                    $badgeClass = $statusColors[$post->status] ?? 'bg-gray-100 text-gray-800';
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.$badgeClass.'">
                        '.ucfirst($post->status).'
                    </span>';
                })
                ->addColumn('featured_badge', function($post) {
                    if ($post->is_featured) {
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-star mr-1"></i> Featured
                        </span>';
                    }
                    return '<span class="text-gray-400">-</span>';
                })
                ->addColumn('actions', function($post) {
                    return '<div class="flex items-center space-x-2">
                        <a href="'.route('posts.show', $post->slug).'" 
                        class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                        target="_blank" 
                        title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="text-green-600 hover:text-green-900 transition-colors duration-200 edit-post" 
                                data-id="'.$post->id.'" 
                                title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900 transition-colors duration-200 delete-post" 
                                data-id="'.$post->id.'" 
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>';
                })
                ->editColumn('created_at', function($post) {
                    return $post->created_at->format('M d, Y');
                })
                ->rawColumns(['image', 'title_column', 'category_badge', 'status_badge', 'featured_badge', 'actions'])
                ->make(true);

        } catch (\Exception $e) {
            \Log::error('Error loading posts data: ' . $e->getMessage());
            return response()->json(['error' => 'Error loading posts'], 500);
        }
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'upload' => 'required|image|max:2048', // 2MB max
            ]);

            if ($request->hasFile('upload')) {
                $file = $request->file('upload');
                
                // Generate unique filename
                $filename = 'editor_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Store file
                $path = $file->storeAs('editor', $filename, 'public');
                
                $url = Storage::url($path);
                
                return response()->json([
                    'uploaded' => true,
                    'url' => $url
                ]);
            }

            return response()->json([
                'uploaded' => false,
                'error' => ['message' => 'No file uploaded']
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'uploaded' => false,
                'error' => ['message' => $e->getMessage()]
            ], 500);
        }
    }


    public function create()
    {
        $categories = Category::where('is_active', true)->get(['id', 'name']);
        return response()->json([
            'success' => true,
            'html' => view('admin.posts.partials.post-form', compact('categories'))->render()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'excerpt' => 'required|string|max:500',
                'category_id' => 'required|exists:categories,id',
                'featured_image' => 'nullable|image|max:2048',
                'status' => 'required|in:draft,published,archived',
                'is_featured' => 'sometimes|boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:255',
            ]);

            // Generate unique slug
            $slug = $this->generateUniqueSlug($request->title);

            // Handle featured image
            $imagePath = null;
            if ($request->hasFile('featured_image')) {
                $imagePath = $request->file('featured_image')->store('posts', 'public');
            }

            $postData = [
                'title' => $validated['title'],
                'slug' => $slug,
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'],
                'category_id' => $validated['category_id'],
                'status' => $validated['status'],
                'is_featured' => $request->boolean('is_featured'),
                'user_id' => auth()->id(),
            ];

            if ($imagePath) {
                $postData['featured_image'] = $imagePath;
            }

            // Set published_at if publishing for the first time
            if ($validated['status'] === 'published') {
                $postData['published_at'] = now();
            }

            $post = Post::create($postData);

            // Create SEO data
            if ($request->meta_title || $request->meta_description || $request->meta_keywords) {
                $post->seo()->create([
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keywords' => $request->meta_keywords,
                    'og_title' => $request->meta_title,
                    'og_description' => $request->meta_description,
                    'og_image' => $imagePath,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully!',
                'data' => $post
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating post: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Post $post)
    {
        $categories = Category::where('is_active', true)->get(['id', 'name']);
        return response()->json([
            'success' => true,
            'html' => view('admin.posts.partials.post-form', compact('post', 'categories'))->render()
        ]);
    }

    public function update(Request $request, Post $post)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'excerpt' => 'required|string|max:500',
                'category_id' => 'required|exists:categories,id',
                'featured_image' => 'nullable|image|max:2048',
                'status' => 'required|in:draft,published,archived',
                'is_featured' => 'sometimes|boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:255',
            ]);

            // Generate unique slug if title changed
            if ($post->title !== $request->title) {
                $slug = $this->generateUniqueSlug($request->title, $post->id);
                $post->slug = $slug;
            }

            // Handle featured image
            if ($request->hasFile('featured_image')) {
                // Delete old image
                if ($post->featured_image) {
                    Storage::disk('public')->delete($post->featured_image);
                }
                $post->featured_image = $request->file('featured_image')->store('posts', 'public');
            }

            $post->title = $validated['title'];
            $post->content = $validated['content'];
            $post->excerpt = $validated['excerpt'];
            $post->category_id = $validated['category_id'];
            $post->status = $validated['status'];
            $post->is_featured = $request->boolean('is_featured');

            // Set published_at if publishing for the first time
            if ($validated['status'] === 'published' && !$post->published_at) {
                $post->published_at = now();
            }

            $post->save();

            // Update or create SEO data
            if ($request->meta_title || $request->meta_description || $request->meta_keywords) {
                $post->seo()->updateOrCreate(
                    ['seoable_id' => $post->id, 'seoable_type' => Post::class],
                    [
                        'meta_title' => $request->meta_title,
                        'meta_description' => $request->meta_description,
                        'meta_keywords' => $request->meta_keywords,
                        'og_title' => $request->meta_title,
                        'og_description' => $request->meta_description,
                        'og_image' => $post->featured_image,
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully!',
                'data' => $post
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating post: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Post $post)
    {
        try {
            $post->delete();
            return response()->json([
                'success' => true,
                'message' => 'Post moved to trash successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting post: ' . $e->getMessage()
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            $post = Post::withTrashed()->findOrFail($id);
            if (!$post->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post is not in trash.'
                ], 422);
            }

            $post->restore();
            return response()->json([
                'success' => true,
                'message' => 'Post restored successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error restoring post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error restoring post: ' . $e->getMessage()
            ], 500);
        }
    }

    public function forceDestroy($id)
    {
        try {
            $post = Post::withTrashed()->findOrFail($id);

            // Delete featured image if exists
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            // Delete SEO data
            $post->seo()->delete();
            $post->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Post permanently deleted.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error force deleting post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting post: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate unique slug for posts
     */
    private function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $counter = 1;
        $originalSlug = $slug;
        
        $query = Post::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
            
            $query = Post::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }
}