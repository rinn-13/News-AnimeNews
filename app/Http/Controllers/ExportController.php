<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PostsExport;
use App\Exports\PostExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    public function exportPostsPDF(Request $request)
    {
        try {
            $query = Post::with(['category', 'user', 'tags']);

            // Filter jika ada
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%")
                      ->orWhereHas('category', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('tags', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }

            if ($request->has('category') && !empty($request->category)) {
                $query->where('category_id', $request->category);
            }

            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            $posts = $query->latest()->get();

            $pdf = Pdf::loadView('exports.posts-pdf', compact('posts'));
            return $pdf->download('daftar-berita-' . date('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }

    public function exportPostsExcel(Request $request)
    {
        try {
            $filters = $request->only(['search', 'category', 'status']);
            return Excel::download(new PostsExport($filters), 'daftar-berita-' . date('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            \Log::error('Excel Export Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor Excel: ' . $e->getMessage());
        }
    }

    public function exportPostExcel($id)
    {
        try {
            $post = Post::findOrFail($id);
            return Excel::download(new PostExport($id), 'berita-' . $post->slug . '-' . date('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            \Log::error('Single Post Excel Export Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor berita ke Excel: ' . $e->getMessage());
        }
    }

    public function exportPostPDF($id)
    {
        try {
            $post = Post::with(['category', 'user', 'tags'])->findOrFail($id);
            
            $pdf = Pdf::loadView('exports.single-post-pdf', compact('post'));
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');
            
            return $pdf->download('berita-' . Str::slug($post->title) . '-' . date('Y-m-d') . '.pdf');
            
        } catch (\Exception $e) {
            \Log::error('Single Post PDF Export Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }
}