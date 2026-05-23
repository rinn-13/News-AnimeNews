@extends('layouts.app')

@section('title', 'Manajemen Tags')
@section('icon', 'fa-tags')

@section('actions')
    <a href="{{ route('tags.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Tambah Tag
    </a>
@endsection

@section('content')
<div class="card news-card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Daftar Tags</h5>
    </div>
    <div class="card-body">
        @if($tags->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Tag</th>
                        <th>Slug</th>
                        <th>Jumlah Berita</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tags as $tag)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $tag->name }}</strong>
                        </td>
                        <td>
                            <code>{{ $tag->slug }}</code>
                        </td>
                        <td>
                            <span class="badge bg-info badge-news">{{ $tag->posts_count }} Berita</span>
                        </td>
                        <td>{{ $tag->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('tags.show', $tag) }}" class="btn btn-info" 
                                   data-bs-toggle="tooltip" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tags.edit', $tag) }}" class="btn btn-warning"
                                   data-bs-toggle="tooltip" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('tags.destroy', $tag) }}" method="POST" 
                                      class="d-inline" onsubmit="return confirm('Hapus tag ini?')">
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
        @else
        <div class="text-center py-5">
            <i class="fas fa-tags fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Belum ada tag</h5>
            <p class="text-muted">Silakan tambah tag baru untuk mengorganisir berita.</p>
            <a href="{{ route('tags.create') }}" class="btn btn-primary mt-3">
                <i class="fas fa-plus me-2"></i>Tambah Tag
            </a>
        </div>
        @endif
    </div>
</div>
@endsection