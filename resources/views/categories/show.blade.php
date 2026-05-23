@extends('layouts.app')

@section('title', 'Detail Kategori')
@section('icon', 'fa-folder')

@section('actions')
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
        <i class="fas fa-edit me-2"></i>Edit
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card news-card">
            <div class="card-body">
                <h1 class="card-title h3">{{ $category->name }}</h1>
                
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="badge bg-primary badge-news">
                        <i class="fas fa-link me-1"></i>{{ $category->slug }}
                    </span>
                    <span class="badge bg-success badge-news">
                        <i class="fas fa-newspaper me-1"></i>{{ $category->posts_count }} Berita
                    </span>
                    <span class="badge bg-info badge-news">
                        <i class="fas fa-calendar me-1"></i>{{ $category->created_at->format('d M Y') }}
                    </span>
                </div>

                <div class="berita-terkait">
                    <h5 class="text-muted">Berita dalam kategori ini:</h5>
                    @if($category->posts->count() > 0)
                    <div class="list-group">
                        @foreach($category->posts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $post->title }}</h6>
                                <small>{{ $post->created_at->format('d M Y') }}</small>
                            </div>
                            <p class="mb-1">{{ Str::limit($post->content, 100) }}</p>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Belum ada berita dalam kategori ini.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Actions -->
        <div class="card news-card">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Kategori
                    </a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Hapus Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection