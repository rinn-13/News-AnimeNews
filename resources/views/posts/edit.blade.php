@extends('layouts.app')

@section('title', 'Edit Berita')
@section('icon', 'fa-edit')

@section('actions')
    <a href="{{ route('posts.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-primary">
            <i class="fas fa-edit me-2"></i>Edit Berita
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data" id="editPostForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Judul Berita <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $post->title) }}" 
                                       placeholder="Masukkan judul berita" required maxlength="255">
                                <div class="form-text" id="title-counter">0/255 karakter</div>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label fw-bold">Konten Berita <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          id="content" name="content" rows="15" 
                                          placeholder="Tulis konten berita di sini..." required>{{ old('content', $post->content) }}</textarea>
                                <div class="form-text" id="content-counter">0 karakter</div>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Thumbnail Section - DIPERBAIKI -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-image me-2 text-primary"></i>Thumbnail</h6>
                        </div>
                        <div class="card-body">
                            <!-- Current Thumbnail -->
                            @if($post->thumbnail)
                                <div class="current-thumbnail text-center mb-3">
                                    <img src="{{ $post->thumbnail_url }}" 
                                         alt="Current Thumbnail" 
                                         class="img-fluid rounded-3 shadow-sm" 
                                         style="max-height: 150px; width: auto;">
                                    <p class="text-muted small mt-2">Thumbnail saat ini</p>
                                    
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="remove_thumbnail" id="remove_thumbnail" value="1">
                                        <label class="form-check-label text-danger small" for="remove_thumbnail">
                                            <i class="fas fa-trash me-1"></i>Hapus thumbnail saat ini
                                        </label>
                                    </div>
                                </div>
                                <hr>
                            @else
                                <div class="text-center mb-3 p-3 bg-light rounded-3">
                                    <i class="fas fa-image fa-2x text-muted mb-2"></i>
                                    <p class="text-muted small">Tidak ada thumbnail</p>
                                </div>
                            @endif
                            
                            <!-- New Thumbnail Upload -->
                            <div class="upload-container">
                                <label for="thumbnail" class="form-label fw-bold">Upload Thumbnail Baru</label>
                                <div class="border-dashed rounded-3 p-4 text-center bg-light mb-3" 
                                     onclick="document.getElementById('thumbnail').click()"
                                     style="cursor: pointer;">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                    <p class="text-muted small mb-2">Klik untuk upload gambar</p>
                                    <small class="text-muted">Format: JPEG, PNG, JPG, GIF. Maksimal: 2MB</small>
                                </div>
                                
                                <input type="file" class="form-control d-none @error('thumbnail') is-invalid @enderror" 
                                       id="thumbnail" name="thumbnail" accept="image/*">
                                
                                <!-- Preview Thumbnail Baru -->
                                <div class="thumbnail-preview text-center mt-3" style="display: none;">
                                    <img id="thumbnailPreview" src="#" alt="Preview" 
                                         class="img-fluid rounded-3 shadow-sm" style="max-height: 120px;">
                                    <p class="text-success small mt-2">
                                        <i class="fas fa-check-circle me-1"></i>Preview thumbnail baru
                                    </p>
                                </div>
                                
                                @error('thumbnail')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Category & Status -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-cog me-2 text-primary"></i>Pengaturan</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tags" class="form-label fw-bold">Tags</label>
                                <select class="form-select @error('tags') is-invalid @enderror" 
                                        id="tags" name="tags[]" multiple>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" 
                                            {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Gunakan Ctrl+Click untuk memilih multiple tags</div>
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Status Publikasi</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                                        {{ old('status', $post->status) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="status">
                                        <span id="statusText">{{ $post->status ? 'Published' : 'Draft' }}</span>
                                    </label>
                                </div>
                                <div class="form-text" id="statusHelp">
                                    {{ $post->status ? 'Berita sedang dipublikasikan' : 'Berita dalam status draft' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Berita -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Info Berita</h6>
                        </div>
                        <div class="card-body">
                            <div class="small">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Dibuat:</span>
                                    <span class="fw-bold">{{ $post->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Diupdate:</span>
                                    <span class="fw-bold">{{ $post->updated_at->format('d M Y H:i') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Penulis:</span>
                                    <span class="fw-bold">{{ $post->user->name }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Views:</span>
                                    <span class="fw-bold">{{ number_format($post->views) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        <div>
                            <a href="{{ route('posts.show', $post) }}" target="_blank" class="btn btn-info me-2">
                                <i class="fas fa-eye me-2"></i>Preview
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Berita
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/css/tom-select.css" rel="stylesheet">
<style>
.border-dashed {
    border: 2px dashed #dee2e6 !important;
    transition: all 0.3s ease;
}

.border-dashed:hover {
    border-color: #4361ee !important;
    background: rgba(67, 97, 238, 0.02);
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 10px 12px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.btn-primary {
    background: linear-gradient(45deg, #4361ee, #3a0ca3);
    border: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
}

.form-check-input:checked {
    background-color: #4361ee;
    border-color: #4361ee;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.0.0/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Tom Select for tags
    if (document.getElementById('tags')) {
        new TomSelect('#tags', {
            plugins: ['remove_button'],
            create: false,
            maxItems: 10,
            placeholder: 'Pilih tags...',
            render: {
                option: function(data, escape) {
                    return '<div class="d-flex align-items-center">' +
                           '<span class="badge bg-primary me-2">Tag</span>' +
                           '<span>' + escape(data.text) + '</span>' +
                           '</div>';
                }
            }
        });
    }

    // Thumbnail preview functionality
    const thumbnailInput = document.getElementById('thumbnail');
    const thumbnailPreview = document.getElementById('thumbnailPreview');
    const previewContainer = document.querySelector('.thumbnail-preview');
    const removeThumbnailCheckbox = document.getElementById('remove_thumbnail');

    if (thumbnailInput) {
        // Click on upload area triggers file input
        document.querySelector('.border-dashed').addEventListener('click', function(e) {
            if (e.target !== thumbnailInput) {
                thumbnailInput.click();
            }
        });

        // Handle file selection
        thumbnailInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File terlalu besar! Maksimal 2MB.');
                    this.value = '';
                    return;
                }

                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak didukung! Gunakan JPEG, PNG, JPG, atau GIF.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    thumbnailPreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                    
                    // Uncheck remove thumbnail if uploading new one
                    if (removeThumbnailCheckbox) {
                        removeThumbnailCheckbox.checked = false;
                    }
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }

    // Character counters
    const titleInput = document.getElementById('title');
    const contentTextarea = document.getElementById('content');

    if (titleInput) {
        const updateTitleCounter = () => {
            const maxLength = 255;
            const currentLength = titleInput.value.length;
            const counter = document.getElementById('title-counter');
            
            counter.textContent = `${currentLength}/${maxLength} karakter`;
            
            if (currentLength > maxLength * 0.9) {
                counter.className = 'form-text text-danger';
            } else if (currentLength > maxLength * 0.7) {
                counter.className = 'form-text text-warning';
            } else {
                counter.className = 'form-text text-muted';
            }
        };
        
        titleInput.addEventListener('input', updateTitleCounter);
        updateTitleCounter();
    }

    if (contentTextarea) {
        const updateContentCounter = () => {
            const currentLength = contentTextarea.value.length;
            const counter = document.getElementById('content-counter');
            
            counter.textContent = `${currentLength.toLocaleString()} karakter`;
            
            if (currentLength > 10000) {
                counter.className = 'form-text text-success';
            } else {
                counter.className = 'form-text text-muted';
            }
        };
        
        contentTextarea.addEventListener('input', updateContentCounter);
        updateContentCounter();
    }

    // Status toggle text
    const statusCheckbox = document.getElementById('status');
    const statusText = document.getElementById('statusText');
    const statusHelp = document.getElementById('statusHelp');

    if (statusCheckbox) {
        statusCheckbox.addEventListener('change', function() {
            if (this.checked) {
                statusText.textContent = 'Published';
                statusHelp.textContent = 'Berita akan dipublikasikan';
            } else {
                statusText.textContent = 'Draft';
                statusHelp.textContent = 'Berita dalam status draft';
            }
        });
    }

    // Form submission handling
    const form = document.getElementById('editPostForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitButton.disabled = true;
            
            // Re-enable after 5 seconds in case of error
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 5000);
        });
    }
});
</script>
@endpush