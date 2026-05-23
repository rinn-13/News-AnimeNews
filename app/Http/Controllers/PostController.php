<?php
// app/Http/Controllers/PostController.php (Updated)
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
{
        $query = Post::with(['category', 'tags', 'user']);

        // Debug logging
        \Log::info('Filter parameters:', $request->all());

        // Search functionality - FIXED
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

        // Filter by category - FIXED
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status - FIXED (handle string '0' and '1')
        if ($request->has('status') && $request->status !== '') {
            $status = $request->status;
            if ($status === '0' || $status === '1') {
                $query->where('status', (bool)$status);
            }
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $posts = $query->latest()->paginate(10);
        $categories = Category::all();

        // Pass request parameters to view for form persistence
        return view('posts.index', compact('posts', 'categories'))->with([
            'search' => $request->search,
            'category' => $request->category,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create', compact('categories', 'tags'));
    }

   public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'tags' => 'array',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'status' => 'boolean',
    ]);

    $data = $request->only(['title', 'content', 'category_id', 'status']);
    $data['slug'] = Str::slug($request->title);
    $data['user_id'] = auth()->id();

    // Handle thumbnail upload - KONSISTEN di public/thumbnails
    if ($request->hasFile('thumbnail')) {
        // Buat folder thumbnails di public
        if (!file_exists(public_path('thumbnails'))) {
            mkdir(public_path('thumbnails'), 0755, true);
        }
        
        $file = $request->file('thumbnail');
        $imageName = 'thumbnail_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Pindahkan file ke public/thumbnails
        $file->move(public_path('thumbnails'), $imageName);
        
        // Simpan path: 'thumbnails/filename.jpg'
        $data['thumbnail'] = 'thumbnails/' . $imageName;
    }

    $post = Post::create($data);

    if ($request->has('tags')) {
        $post->tags()->attach($request->tags);
    }

    return redirect()->route('posts.index')->with('success', 'Berita berhasil dibuat!');
}

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

     public function edit(Post $post)
    {
        // Authorization check - hanya pemilik atau admin yang bisa edit
        if (auth()->user()->role !== 'admin' && $post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();
        $tags = Tag::all();
        
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
{
    // Authorization check
    if (auth()->user()->role !== 'admin' && $post->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'tags' => 'array',
        'tags.*' => 'exists:tags,id',
        'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'status' => 'boolean',
    ]);

    $data = $request->only(['title', 'content', 'category_id', 'status']);
    $data['slug'] = Str::slug($request->title);

    // Handle thumbnail upload - KONSISTEN
    if ($request->hasFile('thumbnail')) {
        // Buat folder thumbnails di public
        if (!file_exists(public_path('thumbnails'))) {
            mkdir(public_path('thumbnails'), 0755, true);
        }

        // Hapus thumbnail lama jika ada
        if ($post->thumbnail && file_exists(public_path($post->thumbnail))) {
            unlink(public_path($post->thumbnail));
        }
        
        $file = $request->file('thumbnail');
        $imageName = 'thumbnail_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Simpan di public/thumbnails
        $file->move(public_path('thumbnails'), $imageName);
        
        // Path: 'thumbnails/filename.jpg'
        $data['thumbnail'] = 'thumbnails/' . $imageName;
    }

    // Handle thumbnail removal
    if ($request->has('remove_thumbnail') && $request->remove_thumbnail) {
        if ($post->thumbnail && file_exists(public_path($post->thumbnail))) {
            unlink(public_path($post->thumbnail));
        }
        $data['thumbnail'] = null;
    }

    $post->update($data);

    // Sync tags
    if ($request->has('tags')) {
        $post->tags()->sync($request->tags);
    } else {
        $post->tags()->detach();
    }

    return redirect()->route('posts.index')
        ->with('success', 'Berita berhasil diperbarui.');
}

        public function toggleStatus(Post $post)
    {
        // Authorization check
        if (auth()->user()->role !== 'admin' && $post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $post->update(['status' => !$post->status]);

        $status = $post->status ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('posts.index')
            ->with('success', "Berita berhasil $status.");
    }

    public function destroy(Post $post)
    {
        // Authorization check
        if (auth()->user()->role !== 'admin' && $post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus thumbnail jika ada
        if ($post->thumbnail && file_exists(public_path($post->thumbnail))) {
            unlink(public_path($post->thumbnail));
        }

        $post->tags()->detach();
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Berita berhasil dihapus.');
    }
}

