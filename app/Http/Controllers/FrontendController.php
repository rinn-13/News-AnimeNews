<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FrontendController extends Controller
{
    public function index()
    {
        try {
            // Berita Utama - Mix antara terbaru dan populer
            $featuredPosts = Post::where('status', true)
                ->with(['category', 'user'])
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();

            // Berita Terbaru (created_at terbaru)
            $latestPosts = Post::where('status', true)
                ->with(['category', 'user'])
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get();

            // Berita Terkini (updated_at terbaru - baru di-update)
            $recentUpdatedPosts = Post::where('status', true)
                ->with(['category', 'user'])
                ->orderBy('updated_at', 'desc')
                ->where('updated_at', '>', Carbon::now()->subDays(3))
                ->take(6)
                ->get();

            // Berita Populer (views tertinggi)
            $popularPosts = Post::where('status', true)
                ->with(['category', 'user'])
                ->orderBy('views', 'desc')
                ->take(6)
                ->get();

            $categories = Category::withCount(['posts' => function($query) {
                $query->where('status', true);
            }])->orderBy('name')->get();

            return view('frontend.index', compact(
                'featuredPosts', 
                'latestPosts', 
                'recentUpdatedPosts', 
                'popularPosts',
                'categories'
            ));

        } catch (\Exception $e) {
            \Log::error('Error in frontend index: ' . $e->getMessage());
            return view('frontend.index')->with('error', 'Terjadi kesalahan saat memuat berita.');
        }
    }

    public function posts(Request $request)
    {
        try {
            $query = Post::where('status', true)
                ->with(['category', 'user', 'tags']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('category', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('tags', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                });
            }

            // Filter by category
            if ($request->filled('category')) {
                $category = Category::where('slug', $request->category)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }

            // Filter by type
            $filterType = $request->type ?? $request->sort ?? null;
            
            if ($filterType) {
                switch ($filterType) {
                    case 'popular':
                        $query->orderBy('views', 'desc');
                        break;
                    case 'recent_updated':
                    case 'recent':
                        $query->orderBy('updated_at', 'desc')
                            ->where('updated_at', '>', now()->subDays(7));
                        break;
                    case 'latest':
                    default:
                        $query->orderBy('created_at', 'desc');
                        break;
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $posts = $query->paginate(12);
            $categories = Category::withCount(['posts' => function($query) {
                $query->where('status', true);
            }])->orderBy('name')->get();
            
            $popularTags = Tag::withCount(['posts' => function($query) {
                $query->where('status', true);
            }])->orderBy('name')->take(15)->get();

            return view('frontend.posts', compact('posts', 'categories', 'popularTags'));

        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mencari berita.');
        }
    }

    // Method untuk live search
    public function liveSearch(Request $request)
    {
        try {
            $query = $request->get('query');
            
            if (strlen($query) < 2) {
                return response()->json([]);
            }

            $posts = Post::where('status', true)
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%");
                })
                ->with(['category', 'user'])
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get(['id', 'title', 'slug', 'thumbnail', 'category_id', 'user_id', 'created_at']);

            $results = $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'thumbnail' => $post->thumbnail ? asset($post->thumbnail) : null,
                    'category' => $post->category->name,
                    'date' => $post->created_at->format('d M Y'),
                    'url' => route('frontend.posts.show', $post->slug)
                ];
            });

            return response()->json($results);

        } catch (\Exception $e) {
            \Log::error('Live search error: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function popularPosts()
    {
        try {
            $posts = Post::where('status', true)
                ->with(['category', 'user', 'tags'])
                ->orderBy('views', 'desc')
                ->paginate(12);

            $categories = Category::withCount(['posts' => function($query) {
                $query->where('status', true);
            }])->orderBy('name')->get();
            
            $popularTags = Tag::withCount(['posts' => function($query) {
                $query->where('status', true);
            }])->orderBy('name')->take(15)->get();

            return view('frontend.posts', compact('posts', 'categories', 'popularTags'))
                ->with('activeFilter', 'popular');

        } catch (\Exception $e) {
            \Log::error('Error in popular posts: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat berita populer.');
        }
    }

    public function recentUpdatedPosts()
    {
        try {
            $posts = Post::where('status', true)
                ->with(['category', 'user', 'tags'])
                ->orderBy('updated_at', 'desc')
                ->where('updated_at', '>', now()->subDays(7))
                ->paginate(12);

            $categories = Category::withCount(['posts' => function($query) {
                $query->where('status', true);
            }])->orderBy('name')->get();
            
            $popularTags = Tag::withCount(['posts' => function($query) {
                $query->where('status', true);
            }])->orderBy('name')->take(15)->get();

            return view('frontend.posts', compact('posts', 'categories', 'popularTags'))
                ->with('activeFilter', 'recent_updated');

        } catch (\Exception $e) {
            \Log::error('Error in recent updated posts: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat berita terkini.');
        }
    }

    public function show($slug)
    {
        try {
            $post = Post::where('slug', $slug)
                ->where('status', true)
                ->with(['category', 'user', 'tags'])
                ->firstOrFail();

            // Increment views
            $post->increment('views');

            $relatedPosts = Post::where('status', true)
                ->where('category_id', $post->category_id)
                ->where('id', '!=', $post->id)
                ->with(['category', 'user'])
                ->latest()
                ->take(4)
                ->get();

            $categories = Category::withCount(['posts' => function($query) {
                $query->where('status', true);
            }])->orderBy('name')->get();

            $popularPosts = Post::where('status', true)
                ->with(['category', 'user'])
                ->orderBy('views', 'desc')
                ->take(5)
                ->get();

            return view('frontend.show', compact('post', 'relatedPosts', 'categories', 'popularPosts'));

        } catch (\Exception $e) {
            \Log::error('Error in frontend show: ' . $e->getMessage());
            abort(404);
        }
    }

    public function category($slug)
    {
        try {
            $category = Category::where('slug', $slug)->firstOrFail();

            $posts = Post::where('status', true)
                ->where('category_id', $category->id)
                ->with(['category', 'user', 'tags'])
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            $categories = Category::withCount(['posts' => function($query) {
                $query->where('status', true);
            }])->orderBy('name')->get();

            $popularTags = Tag::withCount(['posts' => function($query) {
                $query->where('status', true);
            }])->orderBy('name')->take(15)->get();

            return view('frontend.category', compact('posts', 'category', 'categories', 'popularTags'));

        } catch (\Exception $e) {
            \Log::error('Error in frontend category: ' . $e->getMessage());
            abort(404);
        }
    }

    public function tag($slug)
    {
        try {
            $tag = Tag::where('slug', $slug)->firstOrFail();

            $posts = $tag->posts()
                ->where('status', true)
                ->with(['category', 'user', 'tags'])
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            $categories = Category::withCount(['posts' => function($query) {
                $query->where('status', true);
            }])->orderBy('name')->get();

            $popularTags = Tag::withCount(['posts' => function($query) {
                $query->where('status', true);
            }])->orderBy('name')->take(15)->get();

            return view('frontend.tag', compact('posts', 'tag', 'categories', 'popularTags'));

        } catch (\Exception $e) {
            \Log::error('Error in frontend tag: ' . $e->getMessage());
            abort(404);
        }
    }
}