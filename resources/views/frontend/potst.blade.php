@extends('frontend.layout')

@section('title')
    @if(request('search'))
        Hasil Pencarian: "{{ request('search') }}" - Portal Berita
    @else
        Semua Berita - Portal Berita
    @endif
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Debug Info (Hapus setelah fix) -->
            @if(env('APP_DEBUG'))
            <div class="alert alert-warning mb-4">
                <strong>Debug Info:</strong><br>
                Search Term: "{{ request('search') }}"<br>
                Results: {{ $posts->total() }} berita<br>
                Query: {{ request()->getQueryString() }}
            </div>
            @endif

            <h1 class="mb-4">
                @if(request('search'))
                    <i class="fas fa-search me-2 text-primary"></i>
                    Hasil Pencarian: "{{ request('search') }}"
                @else
                    <i class="fas fa-newspaper me-2 text-primary"></i>
                    Semua Berita
                @endif
            </h1>

            <!-- Search Info -->
            @if(request('search'))
            <div class="alert alert-info mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-info-circle me-2"></i>
                        Menampilkan <strong>{{ $posts->total() }}</strong> hasil untuk 
                        "<strong>{{ request('search') }}</strong>"
                    </div>
                    <a href="{{ route('frontend.posts') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Hapus Filter
                    </a>
                </div>
            </div>
            @endif

            @if($posts->count() > 0)
            <div class="row">
                @foreach($posts as $post)
                <div class="col-md-6 col-lg-4 mb-4">
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
                            <span class="badge bg-primary badge-news">{{ $post->category->name }}</span>
                            <h6 class="card-title mt-2">
                                <a href="{{ route('frontend.posts.show', $post->slug) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ $post->title }}
                                </a>
                            </h6>
                            <p class="card-text small text-muted">
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

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->appends(request()->query())->links() }}
            </div>

            @else
            <div class="text-center py-5">
                @if(request('search'))
                <div class="mb-4">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Tidak ada hasil ditemukan</h4>
                    <p class="text-muted mb-3">
                        Tidak ada berita yang sesuai dengan pencarian "<strong>{{ request('search') }}</strong>"
                    </p>
                </div>
                @else
                <div class="mb-4">
                    <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum ada berita</h4>
                    <p class="text-muted mb-3">
                        Silakan kembali nanti untuk melihat berita terbaru.
                    </p>
                </div>
                @endif
                
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('frontend.posts') }}" class="btn btn-primary">
                        <i class="fas fa-newspaper me-2"></i>Lihat Semua Berita
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Search Widget -->
            <div class="card news-card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-search me-2"></i>Cari Berita</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.posts') }}" method="GET" id="sidebarSearchForm">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Kata kunci..." 
                                   value="{{ request('search') }}"
                                   minlength="2" 
                                   required
                                   id="sidebarSearchInput">
                            <button class="btn btn-primary" type="submit" id="sidebarSearchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        @if(request('search'))
                        <div class="mt-2">
                            <a href="{{ route('frontend.posts') }}" class="btn btn-sm btn-outline-secondary w-100">
                                <i class="fas fa-times me-1"></i>Hapus Pencarian
                            </a>
                        </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Categories -->
            <div class="card news-card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-folder me-2"></i>Kategori</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($categories as $category)
                        <a href="{{ route('frontend.categories.show', $category->slug) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            {{ $category->name }}
                            <span class="badge bg-success rounded-pill">{{ $category->posts_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Popular Tags -->
            @if($popularTags->count() > 0)
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
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Enhanced search functionality
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Search page loaded');
        
        // Log form submissions
        document.getElementById('navbarSearchForm')?.addEventListener('submit', function(e) {
            console.log('Navbar search submitted:', document.getElementById('navbarSearchInput').value);
        });
        
        document.getElementById('sidebarSearchForm')?.addEventListener('submit', function(e) {
            console.log('Sidebar search submitted:', document.getElementById('sidebarSearchInput').value);
        });

        // Auto-focus search input
        const searchParam = new URLSearchParams(window.location.search).get('search');
        if (searchParam) {
            document.getElementById('navbarSearchInput')?.focus();
            document.getElementById('sidebarSearchInput')?.focus();
        }
    });
</script>
@endpush