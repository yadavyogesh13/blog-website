<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use App\Models\Analytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'total_categories' => Category::count(),
            'total_views' => Post::sum('views'),
        ];

        $recent_posts = Post::with('category', 'user')
            ->latest()
            ->take(5)
            ->get();

        $popular_posts = Post::with('category')
            ->popular()
            ->take(5)
            ->get();

        $views_data = Analytics::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as views')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recent_posts', 'popular_posts', 'views_data'));
    }
}