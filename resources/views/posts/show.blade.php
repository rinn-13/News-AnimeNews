@extends('layouts.app')

@section('title', 'Detail Berita')
@section('icon', 'fa-eye')

@section('actions')
    <div class="btn-group">
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>Edit
        </a>
        <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" 
                data-bs-toggle="dropdown" aria-expanded="false">
            <span class="visually-hidden">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="{{ route('frontend.posts.show', $post->slug) }}" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>Lihat di Frontend
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="{{ route('export.posts.pdf.single', $post->id) }}">
                    <i class="fas fa-file-pdf me-2"></i>Export PDF
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('export.posts.excel.single', $post->id) }}">
                    <i class="fas fa-file-excel me-2 text-success"></i>Export Excel
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card news-card">
            @if($post->thumbnail)
        <div class="thumbnail-container">
            <img src="{{ asset($post->thumbnail) }}" 
                alt="{{ $post->title }}" class="card-img-top">
        </div>
        @else
        <div class="thumbnail-container bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
            <div class="text-center">
                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada thumbnail</p>
            </div>
        </div>
        @endif
            <div class="card-body">
                <h1 class="card-title h3">{{ $post->title }}</h1>
                
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="badge bg-primary badge-news">
                        <i class="fas fa-folder me-1"></i>{{ $post->category->name }}
                    </span>
                    <span class="badge bg-success badge-news">
                        <i class="fas fa-user me-1"></i>{{ $post->user->name }}
                    </span>
                    <span class="badge bg-info badge-news">
                        <i class="fas fa-calendar me-1"></i>{{ $post->created_at->format('d M Y H:i') }}
                    </span>
                    <span class="badge {{ $post->status ? 'bg-success' : 'bg-danger' }} badge-news">
                        <i class="fas fa-circle me-1"></i>{{ $post->status ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                <div class="tags-section mb-4">
                    <h6 class="text-muted">Tags:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <span class="badge bg-secondary badge-news">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="content-section">
                    <h6 class="text-muted">Konten:</h6>
                    <div class="border rounded p-4 bg-light">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Info Penulis -->
        <div class="card news-card mb-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Penulis</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-circle fa-3x text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">{{ $post->user->name }}</h6>
                        <p class="text-muted mb-1 small">{{ ucfirst($post->user->role) }}</p>
                        <p class="text-muted mb-0 small">
                            Bergabung: {{ $post->user->created_at->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Berita -->
        <div class="card news-card">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <div class="h4 text-primary mb-1">{{ $post->user->posts_count ?? 0 }}</div>
                            <small class="text-muted">Total Berita</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4 text-success mb-1">{{ $todayPosts ?? 0 }}</div>
                        <small class="text-muted">Berita Hari Ini</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card news-card mt-4">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Berita
                    </a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Hapus Berita
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection