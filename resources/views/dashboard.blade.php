@extends('layouts.app')

@section('title', 'Dashboard')
@section('icon', 'fa-tachometer-alt')

@section('actions')
<a href="{{ url('/') }}" target="_blank" class="btn btn-primary">
    <i class="fas fa-external-link-alt me-2"></i>Kunjungi Website
</a>
@endsection

@section('content')
@if(isset($error))
<div class="alert alert-danger alert-modern">
    <i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}
</div>
@endif

<div class="card border-0 bg-light mb-4">
    <div class="card-body py-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="mb-2 text-dark">Selamat Datang, {{ auth()->user()->name }}!</h3>
                <p class="mb-0 text-dark opacity-75">
                    @if(auth()->user()->role === 'admin')
                        <i class="fas fa-crown me-2"></i>Anda login sebagai Administrator - Akses Penuh Sistem
                    @else
                        <i class="fas fa-edit me-2"></i>Anda login sebagai Editor - Kelola Berita & Konten
                    @endif
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="display-6 text-primary opacity-75">
                    @if(auth()->user()->role === 'admin')
                        <i class="fas fa-shield-alt"></i>
                    @else
                        <i class="fas fa-newspaper"></i>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Header Stats - Different for Admin vs Editor -->
<div class="row g-3 mb-4">
    @if(auth()->user()->role === 'admin')
        <!-- Admin Statistics -->
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card-modern border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-primary">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1 fw-bold">{{ number_format($totalPosts) }}</h5>
                            <p class="text-muted mb-1">Total Berita</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                {{ $todayPosts }} hari ini
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card-modern border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-success">
                                <i class="fas fa-folder"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1 fw-bold">{{ number_format($totalCategories) }}</h5>
                            <p class="text-muted mb-0">Kategori</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card-modern border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-info">
                                <i class="fas fa-tags"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1 fw-bold">{{ number_format($totalTags) }}</h5>
                            <p class="text-muted mb-0">Tags</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card-modern border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-warning">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1 fw-bold">{{ number_format($userStats->count()) }}</h5>
                            <p class="text-muted mb-0">Pengguna</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Editor Statistics -->
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card-modern border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-primary">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1 fw-bold">{{ number_format($myPosts ?? $totalPosts) }}</h5>
                            <p class="text-muted mb-1">Berita Saya</p>
                            <small class="text-success">
                                <i class="fas fa-arrow-up me-1"></i>
                                {{ $todayPosts }} hari ini
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card-modern border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1 fw-bold">{{ number_format($publishedPosts ?? 0) }}</h5>
                            <p class="text-muted mb-0">Berita Terpublikasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card-modern border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-warning">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1 fw-bold">{{ number_format($draftPosts ?? 0) }}</h5>
                            <p class="text-muted mb-0">Draft</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stats-card-modern border-0 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-info">
                                <i class="fas fa-eye"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1 fw-bold">{{ number_format($popularPosts->sum('views') ?? 0) }}</h5>
                            <p class="text-muted mb-0">Total Views</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Main Content Area -->
<div class="row g-3">
    <!-- Charts Section -->
    <div class="col-lg-8">
        <div class="row g-3 h-100">
            <!-- Monthly Posts Chart -->
            <div class="col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                        <h6 class="mb-0 fw-bold text-dark flex-grow-1">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Statistik Berita per Bulan ({{ $currentYear }})
                            @if(auth()->user()->role === 'editor')
                                <small class="text-muted">- Berita Anda</small>
                            @endif
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if(count($months) > 0 && count($postsCount) > 0 && array_sum($postsCount) > 0)
                        <div class="chart-container">
                            <canvas id="postsChart"></canvas>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada data untuk ditampilkan</p>
                            <a href="{{ route('posts.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Tulis Berita Pertama
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Category Distribution Chart - Only for Admin -->
            @if(auth()->user()->role === 'admin')
            <div class="col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-chart-pie me-2 text-warning"></i>
                            Distribusi Berita per Kategori
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if($categoryStats->count() > 0)
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada data kategori</p>
                            <a href="{{ route('categories.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-folder-plus me-2"></i>Buat Kategori Pertama
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Sidebar Content -->
    <div class="col-lg-4">
        <div class="row g-3 h-100">
            <!-- Quick Actions -->
            <div class="col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-bolt me-2 text-success"></i>Aksi Cepat
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center">
                                <i class="fas fa-plus me-2"></i>Tulis Berita Baru
                            </a>
                            @if(auth()->user()->role === 'admin')
                            <div class="row g-2 mt-1">
                                <div class="col-6">
                                    <a href="{{ route('categories.create') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-folder-plus me-2"></i>Kategori
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('tags.create') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-tag me-2"></i>Tag
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Posts - SIMPLIFIED VERSION WITHOUT THUMBNAILS -->
            <div class="col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-clock me-2 text-info"></i>
                            @if(auth()->user()->role === 'admin')
                                Berita Terbaru
                            @else
                                Berita Terbaru Saya
                            @endif
                        </h6>
                        <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if($recentPosts->count() > 0)
                        <div class="list-group list-group-flush recent-posts-list">
                            @foreach($recentPosts as $post)
                            <a href="{{ route('posts.show', $post) }}" 
                               class="list-group-item list-group-item-action p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0 fw-bold text-truncate flex-grow-1">{{ Str::limit($post->title, 50) }}</h6>
                                    <small class="text-muted flex-shrink-0 ms-2">{{ $post->created_at->format('d/m') }}</small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-light text-dark">{{ $post->category->name ?? 'No Category' }}</span>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-eye me-1 text-muted small"></i>
                                        <small class="text-muted">{{ $post->views ?? 0 }}</small>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-newspaper fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Belum ada berita terbaru</p>
                            <a href="{{ route('posts.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i>Tulis Berita
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Statistics - Only for Admin -->
@if(auth()->user()->role === 'admin')
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-chart-bar me-2 text-secondary"></i>Statistik Kategori
                </h6>
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary">
                    Kelola Kategori
                </a>
            </div>
            <div class="card-body p-4">
                @if($categoryStats->count() > 0)
                <div class="row g-3">
                    @foreach($categoryStats as $stat)
                    <div class="col-md-6 col-lg-3">
                        <div class="category-stat-card p-3 rounded-3 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark small">{{ $stat->name }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $stat->posts_count }}</span>
                            </div>
                            @php
                                $percentage = $totalPosts > 0 ? ($stat->posts_count / $totalPosts) * 100 : 0;
                            @endphp
                            <div class="progress mb-2">
                                <div class="progress-bar bg-gradient-primary" role="progressbar" 
                                     style="width: {{ $percentage }}%"
                                     aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted">{{ number_format($percentage, 1) }}% dari total</small>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-folder-open fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Belum ada data kategori</p>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-folder-plus me-2"></i>Buat Kategori Pertama
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
:root {
    --primary: #4361ee;
    --primary-dark: #3a0ca3;
    --success: #4cc9f0;
    --warning: #f72585;
    --info: #4895ef;
    --light-bg: #f8f9fa;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
}

/* Improved stats card design */
.stats-card-modern {
    background: linear-gradient(135deg, #fff 0%, var(--light-bg) 100%);
    border-radius: 16px;
    transition: all 0.3s ease;
    border: 1px solid rgba(67, 97, 238, 0.1);
    height: 100%;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.stats-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(67, 97, 238, 0.15) !important;
    border-color: rgba(67, 97, 238, 0.2);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    box-shadow: 0 4px 6px rgba(255, 241, 241, 0.1);
}

.stats-icon.bg-primary { background: linear-gradient(45deg, var(--primary), var(--primary-dark)); }
.stats-icon.bg-success { background: linear-gradient(45deg, #4cc9f0, #4895ef); }
.stats-icon.bg-info { background: linear-gradient(45deg, var(--info), #4361ee); }
.stats-icon.bg-warning { background: linear-gradient(45deg, var(--warning), #7209b7); }

/* Improved chart container */
.chart-container {
    position: relative;
    height: 250px;
    width: 100%;
}

/* Improved card styling */
.card {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,0.05);
    background-color: #fff;
}

/* IMPROVED RECENT POSTS LIST - NO THUMBNAILS */
.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.recent-posts-list .list-group-item {
    transition: all 0.3s ease;
    border: none;
    border-bottom: 1px solid #f1f3f4 !important;
}

.recent-posts-list .list-group-item:hover {
    background: #f8f9fa;
    transform: translateX(3px);
}

.recent-posts-list .list-group-item:last-child {
    border-bottom: none !important;
}

.recent-posts-list h6 {
    font-size: 0.9rem;
    line-height: 1.4;
    color: #333;
}

/* Improved category stats cards */
.category-stat-card {
    background: #fff;
    border: 1px solid #f1f3f4;
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.category-stat-card:hover {
    background: #f8f9fa;
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.progress {
    height: 6px;
    border-radius: 10px;
    background: #e9ecef;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.5s ease;
}

.alert-modern {
    border-radius: 12px;
    border: none;
    border-left: 4px solid;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Button improvements */
.btn {
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-lg {
    padding: 0.75rem 1.5rem;
}

.btn-outline-primary, .btn-outline-secondary {
    border-width: 1.5px;
}

/* Improved grid spacing */
.row.g-3 {
    margin-bottom: 1rem;
}

/* Badge improvements */
.badge {
    font-weight: 500;
}

/* Dropdown improvements */
.dropdown-menu {
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.dropdown-item {
    border-radius: 6px;
    margin: 2px 6px;
    width: auto;
}

/* Better spacing for mobile */
@media (max-width: 768px) {
    .stats-card-modern {
        margin-bottom: 1rem;
    }
    
    .chart-container {
        height: 200px;
    }
    
    .recent-posts-list .list-group-item {
        padding: 0.75rem;
    }
    
    .recent-posts-list .list-group-item h6 {
        font-size: 0.85rem;
    }
    
    .card-body.p-4 {
        padding: 1rem !important;
    }
    
    .card-header {
        padding: 0.75rem 1rem;
    }
    
    .btn-lg {
        padding: 0.6rem 1.2rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .recent-posts-list .list-group-item {
        padding: 0.6rem;
    }
    
    .stats-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .card-header h6 {
        font-size: 0.9rem;
    }
    
    .dropdown-toggle {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
}

/* Empty state improvements */
.text-center.py-5, .text-center.py-4 {
    padding: 2rem 1rem !important;
}

.text-center .fa-3x, .text-center .fa-2x {
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* New color scheme improvements */
.bg-primary {
    background-color: var(--primary) !important;
}

.text-primary {
    color: var(--primary) !important;
}

.btn-primary {
    background-color: var(--primary);
    border-color: var(--primary);
}

.btn-primary:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
}

/* Improved recent posts layout */
.recent-posts-list .list-group-item {
    border-radius: 8px;
    margin: 0 8px 8px;
    width: calc(100% - 16px);
}

.recent-posts-list .list-group-item:last-child {
    margin-bottom: 0;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modern Chart Styling
        Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
        Chart.defaults.color = '#6c757d';
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0,0,0,0.8)';
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.cornerRadius = 8;
        Chart.defaults.plugins.legend.labels.boxWidth = 12;
        Chart.defaults.plugins.legend.labels.padding = 15;

        // Monthly Posts Chart
        @if(count($months) > 0 && count($postsCount) > 0 && array_sum($postsCount) > 0)
        const ctx = document.getElementById('postsChart').getContext('2d');
        const postsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Jumlah Berita',
                    data: @json($postsCount),
                    backgroundColor: 'rgba(67, 97, 238, 0.8)',
                    borderColor: 'rgba(67, 97, 238, 1)',
                    borderWidth: 0,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Berita: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { 
                            color: 'rgba(0,0,0,0.05)',
                            drawBorder: false
                        },
                        ticks: { 
                            stepSize: 1,
                            padding: 10
                        }
                    },
                    x: {
                        grid: { 
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            padding: 10
                        }
                    }
                }
            }
        });
        @endif

        // Category Distribution Chart (Admin Only)
        @if(auth()->user()->role === 'admin' && $categoryStats->count() > 0)
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: @json($categoryStats->pluck('name')),
                datasets: [{
                    data: @json($categoryStats->pluck('posts_count')),
                    backgroundColor: [
                        '#4361ee', '#3a0ca3', '#4cc9f0', '#f72585', '#7209b7',
                        '#4895ef', '#560bad', '#b5179e', '#f15bb5', '#00bbf9'
                    ],
                    borderWidth: 0,
                    hoverOffset: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
        @endif

        // Add animation to stats cards on load
        document.querySelectorAll('.stats-card-modern').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate__animated', 'animate__fadeInUp');
        });
    });
</script>
@endpush