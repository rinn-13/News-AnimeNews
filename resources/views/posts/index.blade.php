@extends('layouts.app')

@section('title', 'Manajemen Berita')
@section('icon', 'fa-newspaper')

@section('actions')
    <div class="btn-group">
        <a href="{{ route('posts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Berita
        </a>
        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" 
                data-bs-toggle="dropdown" aria-expanded="false">
            <span class="visually-hidden">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="{{ route('export.posts.pdf', request()->query()) }}">
                    <i class="fas fa-file-pdf me-2"></i>Export PDF
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('export.posts.excel', request()->query()) }}">
                    <i class="fas fa-file-excel me-2 text-success"></i>Export Excel
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
<!-- Filter dan Pencarian - IMPROVED -->
<div class="card news-card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filter & Pencarian</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('posts.index') }}" method="GET" class="row g-3" id="filterForm">
            <div class="col-md-3">
                <label for="search" class="form-label">Pencarian</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Cari judul, konten, kategori...">
            </div>
            
            <div class="col-md-2">
                <label for="category" class="form-label">Kategori</label>
                <select class="form-select" id="category" name="category">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                            {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="start_date" class="form-label">Dari Tanggal</label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                       value="{{ request('start_date') }}">
            </div>
            
            <div class="col-md-2">
                <label for="end_date" class="form-label">Sampai Tanggal</label>
                <input type="date" class="form-control" id="end_date" name="end_date" 
                       value="{{ request('end_date') }}">
            </div>
            
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>
                </button>
            </div>
            
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Ditemukan: <strong>{{ $posts->total() }}</strong> berita
                        @if(request()->anyFilled(['search', 'category', 'status', 'start_date']))
                            dengan filter
                        @endif
                    </small>
                    @if(request()->anyFilled(['search', 'category', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear Filter
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Berita -->
<div class="card news-card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Berita</h5>
        <span class="badge bg-light text-dark">
            Menampilkan: {{ $posts->count() }} dari {{ $posts->total() }}
        </span>
    </div>
    
    <div class="card-body">
        @if($posts->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th width="100">Thumbnail</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tags</th>
                        <th>Status</th>
                        <th>Penulis</th>
                        <th>Tanggal</th>
                        <th width="150" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                    <tr>
                        <td>{{ $loop->iteration + ($posts->perPage() * ($posts->currentPage() - 1)) }}</td>
                        <!-- PERBAIKAN: Hapus duplikasi tag <td> -->
                        <td>
                            @if($post->thumbnail)
                                <img src="{{ asset($post->thumbnail) }}" 
                                    alt="Thumbnail" class="rounded" width="60" height="60" style="object-fit: cover;"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            @endif
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center {{ $post->thumbnail ? 'd-none' : '' }}" 
                                style="width: 60px; height: 60px;">
                                <i class="fas fa-image text-white"></i>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ Str::limit($post->title, 60) }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ Str::limit(strip_tags($post->content), 50) }}
                                </small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info badge-news">{{ $post->category->name }}</span>
                        </td>
                        <td>
                            @foreach($post->tags->take(2) as $tag)
                                <span class="badge bg-secondary badge-news mb-1">{{ $tag->name }}</span>
                            @endforeach
                            @if($post->tags->count() > 2)
                                <span class="badge bg-light text-dark badge-news">
                                    +{{ $post->tags->count() - 2 }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('posts.toggle-status', $post) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="badge {{ $post->status ? 'bg-success' : 'bg-danger' }} badge-news border-0"
                                        style="cursor: pointer;"
                                        title="Klik untuk {{ $post->status ? 'nonaktifkan' : 'aktifkan' }}"
                                        onclick="return confirm('Yakin ingin {{ $post->status ? 'menonaktifkan' : 'mengaktifkan' }} berita ini?')">
                                    {{ $post->status ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <small>{{ $post->user->name }}</small>
                        </td>
                        <td>
                            <!-- PERBAIKAN: Tambahkan null safety check -->
                            <small>{{ $post->created_at ? $post->created_at->format('d/m/Y') : 'N/A' }}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('posts.show', $post) }}" class="btn btn-info" 
                                   data-bs-toggle="tooltip" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning"
                                   data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" 
                                      class="d-inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            data-bs-toggle="tooltip" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Menampilkan {{ $posts->firstItem() }} - {{ $posts->lastItem() }} dari {{ $posts->total() }} berita
            </div>
            <nav>
                {{ $posts->appends(request()->query())->links() }}
            </nav>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">
                @if(request()->anyFilled(['search', 'category', 'status', 'start_date']))
                    Tidak ada berita yang sesuai dengan filter
                @else
                    Belum ada berita
                @endif
            </h5>
            <p class="text-muted">
                @if(request()->anyFilled(['search', 'category', 'status', 'start_date']))
                    Silakan sesuaikan filter pencarian atau <a href="{{ route('posts.index') }}">reset filter</a>.
                @else
                    Silakan tambah berita baru untuk memulai.
                @endif
            </p>
            <a href="{{ route('posts.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus me-2"></i>Tambah Berita Pertama
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi tooltip
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Auto-submit form ketika select berubah (opsional)
        document.getElementById('status').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('category').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        // Date validation
        document.getElementById('start_date').addEventListener('change', function() {
            var endDate = document.getElementById('end_date');
            if (this.value && endDate.value && this.value > endDate.value) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
                this.value = '';
            }
        });

        document.getElementById('end_date').addEventListener('change', function() {
            var startDate = document.getElementById('start_date');
            if (this.value && startDate.value && this.value < startDate.value) {
                alert('Tanggal akhir tidak boleh lebih kecil dari tanggal mulai');
                this.value = '';
            }
        });
    });
</script>
@endpush