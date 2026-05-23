<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            
            // Jika user adalah Admin
            if ($user->role === 'admin') {
                return $this->adminDashboard($user);
            }
            
            // Jika user adalah Editor
            if ($user->role === 'editor') {
                return $this->editorDashboard($user);
            }
            
            // Fallback untuk role lainnya
            return $this->defaultDashboard($user);

        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return $this->errorDashboard($e->getMessage());
        }
    }

    /**
     * Dashboard untuk Admin (akses penuh)
     */
    private function adminDashboard($user)
    {
        // Basic Statistics - All data
        $totalPosts = Post::count();
        $totalCategories = Category::count();
        $totalTags = Tag::count();
        $todayPosts = Post::whereDate('created_at', today())->count();
        $totalUsers = User::count();
        
        // Recent Posts - All posts
        $recentPosts = Post::with('category', 'user')
            ->latest()
            ->take(5)
            ->get();

        // Category Statistics - All categories
        $categoryStats = Category::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->get();

        // Monthly Posts Data for Chart - All posts
        $currentYear = date('Y');
        $postsPerMonth = Post::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', $currentYear)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Prepare chart data
        $months = [];
        $postsCount = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::createFromDate($currentYear, $i, 1)->format('F');
            $months[] = $monthName;
            
            $monthData = $postsPerMonth->where('month', $i)->first();
            $postsCount[] = $monthData ? $monthData->count : 0;
        }

        // Popular Posts (by views) - All posts
        $popularPosts = Post::with('category')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // User Statistics
        $userStats = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->get();

        // Recent Users
        $recentUsers = User::latest()->take(5)->get();

        return view('dashboard', [
            'user' => $user,
            'role' => 'admin',
            'totalPosts' => $totalPosts,
            'totalCategories' => $totalCategories,
            'totalTags' => $totalTags,
            'todayPosts' => $todayPosts,
            'totalUsers' => $totalUsers,
            'recentPosts' => $recentPosts,
            'categoryStats' => $categoryStats,
            'months' => $months,
            'postsCount' => $postsCount,
            'popularPosts' => $popularPosts,
            'userStats' => $userStats,
            'recentUsers' => $recentUsers,
            'currentYear' => $currentYear,
        ]);
    }

    /**
     * Dashboard untuk Editor (hanya data miliknya)
     */
    private function editorDashboard($user)
    {
        // Basic Statistics - Only user's posts
        $myPosts = Post::where('user_id', $user->id)->count();
        $publishedPosts = Post::where('user_id', $user->id)->where('status', 'published')->count();
        $draftPosts = Post::where('user_id', $user->id)->where('status', 'draft')->count();
        $todayPosts = Post::where('user_id', $user->id)->whereDate('created_at', today())->count();
        
        // Recent Posts - Only user's posts
        $recentPosts = Post::with('category')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Category Statistics - Only categories used by user's posts
        $categoryStats = Category::whereHas('posts', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->withCount(['posts' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('posts_count', 'desc')
            ->get();

        // Monthly Posts Data for Chart - Only user's posts
        $currentYear = date('Y');
        $postsPerMonth = Post::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('user_id', $user->id)
        ->whereYear('created_at', $currentYear)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Prepare chart data
        $months = [];
        $postsCount = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::createFromDate($currentYear, $i, 1)->format('F');
            $months[] = $monthName;
            
            $monthData = $postsPerMonth->where('month', $i)->first();
            $postsCount[] = $monthData ? $monthData->count : 0;
        }

        // Popular Posts (by views) - Only user's posts
        $popularPosts = Post::with('category')
            ->where('user_id', $user->id)
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // Weekly activity
        $weeklyActivity = Post::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard', [
            'user' => $user,
            'role' => 'editor',
            'myPosts' => $myPosts,
            'publishedPosts' => $publishedPosts,
            'draftPosts' => $draftPosts,
            'todayPosts' => $todayPosts,
            'recentPosts' => $recentPosts,
            'categoryStats' => $categoryStats,
            'months' => $months,
            'postsCount' => $postsCount,
            'popularPosts' => $popularPosts,
            'weeklyActivity' => $weeklyActivity,
            'currentYear' => $currentYear,
        ]);
    }

    /**
     * Default dashboard for other roles
     */
    private function defaultDashboard($user)
    {
        return view('dashboard', [
            'user' => $user,
            'role' => 'user',
            'message' => 'Selamat datang di dashboard News CMS'
        ]);
    }

    /**
     * Error dashboard
     */
    private function errorDashboard($errorMessage)
    {
        return view('dashboard', [
            'error' => 'Terjadi kesalahan saat memuat dashboard: ' . $errorMessage,
            'role' => 'error'
        ]);
    }

    /**
     * Get thumbnail debug information for a post
     */
    private function getThumbnailDebugInfo(Post $post)
    {
        if (!$post->thumbnail) {
            return [
                'has_thumbnail' => false,
                'database_value' => null,
                'possible_paths' => [],
                'working_url' => null
            ];
        }

        $possiblePaths = [
            'storage/' . $post->thumbnail,
            'storage/thumbnails/' . $post->thumbnail,
            $post->thumbnail,
            'thumbnails/' . $post->thumbnail
        ];

        $debugInfo = [
            'has_thumbnail' => true,
            'database_value' => $post->thumbnail,
            'possible_paths' => [],
            'working_url' => null
        ];

        foreach ($possiblePaths as $path) {
            $fullPath = public_path($path);
            $url = asset($path);
            $exists = file_exists($fullPath);
            
            $debugInfo['possible_paths'][$path] = [
                'exists' => $exists,
                'url' => $url,
                'full_path' => $fullPath
            ];

            if ($exists && !$debugInfo['working_url']) {
                $debugInfo['working_url'] = $url;
            }
        }

        return $debugInfo;
    }

    /**
     * Get the correct thumbnail URL for a post
     */
    public function getThumbnailUrl(Post $post)
    {
        if (!$post->thumbnail) {
            return null;
        }

        $possiblePaths = [
            'storage/' . $post->thumbnail,
            'storage/thumbnails/' . $post->thumbnail,
            $post->thumbnail
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        return null;
    }
}