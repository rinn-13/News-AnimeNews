@extends('frontend.layout')

@section('title', $post->title . ' - Portal Berita')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('frontend.categories.show', $post->category->slug) }}">{{ $post->category->name }}</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($post->title, 30) }}</li>
                    </ol>
                </nav>

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

                <span class="badge bg-primary">{{ $post->category->name }}</span>
                <h1 class="mb-3">{{ $post->title }}</h1>

                <div class="d-flex flex-wrap gap-3 mb-4 text-muted">
                    <span>
                        <i class="fas fa-user me-1"></i>Oleh: {{ $post->user->name }}
                    </span>
                    <span>
                        <i class="fas fa-calendar me-1"></i>{{ $post->created_at->format('d F Y') }}
                    </span>
                    <span>
                        <i class="fas fa-clock me-1"></i>{{ $post->created_at->format('H:i') }}
                    </span>
                    <span>
                        <i class="fas fa-eye me-1"></i>{{ $post->views }} views
                    </span>
                </div>

                <div class="content mb-5">
                    {!! nl2br(e($post->content)) !!}
                </div>

                <!-- Tags -->
                @if($post->tags->count() > 0)
                <div class="tags-section mb-5">
                    <h6>Tags:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('frontend.tags.show', $tag->slug) }}" 
                           class="badge bg-secondary text-decoration-none">
                            {{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Share Buttons -->
                <div class="share-section mb-5">
                    <h6>Bagikan:</h6>
                    <div class="d-flex gap-2">
                        <!-- Facebook Share -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}&quote={{ urlencode($post->title) }}" 
                           target="_blank" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-facebook me-1"></i>Facebook
                        </a>
                        
                        <!-- Twitter Share -->
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}" 
                           target="_blank" 
                           class="btn btn-outline-info btn-sm">
                            <i class="fab fa-twitter me-1"></i>Twitter
                        </a>
                        
                        <!-- WhatsApp Share -->
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" 
                           target="_blank" 
                           class="btn btn-outline-success btn-sm">
                            <i class="fab fa-whatsapp me-1"></i>WhatsApp
                        </a>
                        
                        <!-- Copy Link -->
                        <button onclick="copyToClipboard()" 
                                class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-link me-1"></i>Salin Link
                        </button>
                    </div>
                    
                    <!-- Alert untuk copy link -->
                    <div id="copyAlert" class="alert alert-success mt-2" style="display: none;">
                        Link berhasil disalin!
                    </div>
                </div>
            </article>

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
            <section class="related-posts mt-5">
                <h4 class="mb-4">Berita Terkait</h4>
                <div class="row">
                    @foreach($relatedPosts as $relatedPost)
                    <div class="col-md-6 mb-3">
                        <div class="card news-card h-100">
                            @if($relatedPost->hasThumbnail())
                            <div class="thumbnail-container">
                                <img src="{{ $relatedPost->thumbnail_url }}" 
                                     class="card-img-top" alt="{{ $relatedPost->title }}">
                            </div>
                            @endif
                            <div class="card-body">
                                <span class="badge bg-primary">{{ $relatedPost->category->name }}</span>
                                <h6 class="card-title mt-2">
                                    <a href="{{ route('frontend.posts.show', $relatedPost->slug) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ Str::limit($relatedPost->title, 50) }}
                                    </a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
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
                        @foreach($categories as $category)
                        <a href="{{ route('frontend.categories.show', $category->slug) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            {{ $category->name }}
                            <span class="badge bg-primary rounded-pill">{{ $category->posts_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Popular Posts -->
            <div class="card news-card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Populer</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($popularPosts as $popularPost)
                        <a href="{{ route('frontend.posts.show', $popularPost->slug) }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ Str::limit($popularPost->title, 40) }}</h6>
                            </div>
                            <small class="text-muted">{{ $popularPost->created_at->format('d M Y') }}</small>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    // Buat URL lengkap dengan judul
    const currentUrl = window.location.href;
    const title = "{{ $post->title }}";
    const textToCopy = `${title} - ${currentUrl}`;
    
    // Gunakan Clipboard API modern
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(textToCopy)
            .then(() => {
                showCopyAlert();
            })
            .catch(err => {
                // Fallback untuk browser lama
                fallbackCopyTextToClipboard(textToCopy);
            });
    } else {
        // Fallback untuk browser yang tidak support Clipboard API
        fallbackCopyTextToClipboard(textToCopy);
    }
}

function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    
    // Hindari scroll ke bawah
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopyAlert();
        }
    } catch (err) {
        console.error('Fallback: Gagal menyalin teks', err);
    }
    
    document.body.removeChild(textArea);
}

function showCopyAlert() {
    const alert = document.getElementById('copyAlert');
    alert.style.display = 'block';
    
    // Sembunyikan alert setelah 3 detik
    setTimeout(() => {
        alert.style.display = 'none';
    }, 3000);
}
</script>

<style>
.thumbnail-container {
    height: 400px;
    overflow: hidden;
    border-radius: 10px;
    margin-bottom: 20px;
}

.thumbnail-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.news-card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.news-card:hover {
    transform: translateY(-5px);
}

.share-section .btn {
    border-radius: 20px;
    padding: 5px 15px;
}

#copyAlert {
    transition: all 0.3s ease;
}
</style>
@endsection