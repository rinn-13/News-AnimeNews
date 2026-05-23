@extends('frontend.layout')

@section('title', 'Berita Kategori ' . $category->name . ' - Portal Berita')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Kategori: {{ $category->name }}</li>
                </ol>
            </nav>

            <h1 class="mb-4">Berita Kategori: {{ $category->name }}</h1>
            <p class="text-muted mb-4">Menampilkan {{ $posts->total() }} berita dalam kategori ini.</p>

            @if($posts->count() > 0)
            <div class="row">
                @foreach($posts as $post)
                <div class="col-md-6 mb-4">
                    <div class="card news-card h-100">
                        @if($post->thumbnail)
                        <div class="thumbnail-container">
                            <img src="{{ asset($post->thumbnail) }}" 
                                 class="card-img-top" alt="{{ $post->title }}">
                        </div>
                        @endif
                        <div class="card-body">
                            <span class="badge bg-primary badge-news">{{ $post->category->name }}</span>
                            <h5 class="card-title mt-2">
                                <a href="{{ route('frontend.posts.show', $post->slug) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ Str::limit($post->title, 60) }}
                                </a>
                            </h5>
                            <p class="card-text">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>{{ $post->user->name }}
                                <i class="fas fa-calendar ms-2 me-1"></i>{{ $post->created_at->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Tidak ada berita dalam kategori ini</h4>
                <p class="text-muted">Silakan kembali ke halaman semua berita.</p>
                <a href="{{ route('frontend.posts') }}" class="btn btn-primary">Lihat Semua Berita</a>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Categories -->
            <div class="card news-card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Kategori</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($categories as $cat)
                        <a href="{{ route('frontend.categories.show', $cat->slug) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center 
                                  {{ $cat->id == $category->id ? 'active' : '' }}">
                            {{ $cat->name }}
                            <span class="badge bg-primary rounded-pill">{{ $cat->posts_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Popular Tags -->
            <div class="card news-card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Tags Populer</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($popularTags as $tag)
                        <a href="{{ route('frontend.tags.show', $tag->slug) }}" 
                           class="badge bg-secondary text-decoration-none">
                            {{ $tag->name }} ({{ $tag->posts_count }})
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection