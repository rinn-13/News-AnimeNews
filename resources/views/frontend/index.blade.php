@extends('frontend.layout')

@section('title', 'AnimeNews - Berita Anime Terkini dan Terpercaya')

@section('content')
<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(rgba(50, 50, 50, 0.6), rgba(50, 50, 50, 0.6)), url('{{ asset('images/kaito4.png') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="container text-start">
        <h1 class="display-4 fw-bold mb-4">Berita Anime Terpercaya</h1>
        <p class="lead mb-4">Dapatkan informasi terkini dan terupdate seputar dunia anime</p>
        <div class="d-flex justify-content-start gap-3 flex-wrap">
            <a href="{{ route('frontend.posts') }}" class="btn btn-light btn-lg">
                <i class="fas fa-newspaper me-2"></i>Semua Berita
            </a>
            <a href="{{ route('frontend.posts.popular') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-fire me-2"></i>Berita Populer
            </a>
        </div>
    </div>
</section>

<!-- Featured Posts -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">
            <i class="fas fa-star me-2 text-warning"></i>Berita Utama
        </h2>
        
        @if(isset($featuredPosts) && $featuredPosts->count() > 0)
        <div class="row">
            @foreach($featuredPosts as $post)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card news-card h-100">
                    @if($post->thumbnail)
                    <div class="thumbnail-container">
                        <img src="{{ asset($post->thumbnail) }}" 
                            class="card-img-top" alt="{{ $post->title }}">
                    </div>
                    @else
                    <div class="thumbnail-container bg-light d-flex align-items-center justify-content-center">
                        <i class="fas fa-newspaper fa-2x text-muted"></i>
                    </div>
                    @endif
                    <div class="card-body">
                        <span class="badge bg-primary badge-news mb-2">{{ $post->category->name }}</span>
                        <h5 class="card-title">
                            <a href="{{ route('frontend.posts.show', $post->slug) }}" 
                               class="text-decoration-none text-dark">
                                {{ Str::limit($post->title, 60) }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">
                            {{ Str::limit(strip_tags($post->content), 100) }}
                        </p>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>{{ $post->user->name }}
                            </small>
                            <small class="text-muted">
                                {{ $post->created_at->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Belum ada berita</h4>
            <p class="text-muted">Silakan kembali nanti untuk melihat berita terbaru.</p>
        </div>
        @endif
    </div>
</section>

<!-- Recent Posts -->
@if(isset($latestPosts) && $latestPosts->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="fas fa-clock me-2 text-primary"></i>Berita Terbaru
            </h3>
        </div>
        
        <div class="row">
            @foreach($latestPosts as $post)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card news-card h-100">
                    @if($post->thumbnail)
                    <div class="thumbnail-container">
                        <!-- PERBAIKAN: path thumbnail -->
                        <img src="{{ asset($post->thumbnail) }}" 
                             class="card-img-top" alt="{{ $post->title }}"
                             onerror="this.style.display='none'">
                    </div>
                    @else
                    <div class="thumbnail-container bg-light d-flex align-items-center justify-content-center">
                        <i class="fas fa-newspaper fa-2x text-muted"></i>
                    </div>
                    @endif
                    
                    <div class="card-body">
                        <span class="badge bg-primary badge-news mb-2">{{ $post->category->name }}</span>
                        <h6 class="card-title">
                            <a href="{{ route('frontend.posts.show', $post->slug) }}" 
                               class="text-decoration-none text-dark">
                                {{ Str::limit($post->title, 50) }}
                            </a>
                        </h6>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <small class="text-muted">
                            {{ $post->created_at->format('d M Y') }}
                        </small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Popular Posts Section - TAMBAHKAN INI -->
@if(isset($popularPosts) && $popularPosts->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="fas fa-fire me-2 text-danger"></i>Berita Populer
            </h3>
        </div>
        
        <div class="row">
            @foreach($popularPosts as $post)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card news-card h-100">
                    @if($post->thumbnail)
                    <div class="thumbnail-container">
                        <img src="{{ asset($post->thumbnail) }}" 
                            class="card-img-top" alt="{{ $post->title }}">
                    </div>
                    @else
                    <div class="thumbnail-container bg-light d-flex align-items-center justify-content-center">
                        <i class="fas fa-newspaper fa-2x text-muted"></i>
                    </div>
                    @endif
                    <div class="card-body">
                        <span class="badge bg-danger badge-news mb-2">{{ $post->category->name }}</span>
                        <h6 class="card-title">
                            <a href="{{ route('frontend.posts.show', $post->slug) }}" 
                               class="text-decoration-none text-dark">
                                {{ Str::limit($post->title, 50) }}
                            </a>
                        </h6>
                        <p class="card-text small text-muted">
                            <i class="fas fa-eye me-1"></i>{{ $post->views }} views
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Categories Section -->
@if(isset($categories) && $categories->count() > 0)
<section class="py-5">
    <div class="container">
        <h3 class="text-center mb-5">
            <i class="fas fa-folder me-2 text-info"></i>Kategori Berita
        </h3>
        <div class="row">
            @foreach($categories as $category)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                <a href="{{ route('frontend.categories.show', $category->slug) }}" 
                   class="text-decoration-none">
                    <div class="card news-card text-center h-100">
                        <div class="card-body py-4">
                            <i class="fas fa-folder fa-2x text-info mb-2"></i>
                            <h6 class="card-title mb-1">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->posts_count }} berita</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection