<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $post->title }} - {{ config('app.name') }}</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'DejaVu Sans', 'Inter', 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #1a202c;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 50%);
            opacity: 0.3;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .header h1 {
            font-size: 32px;
            margin: 0 0 15px 0;
            font-weight: 700;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .header-meta {
            font-size: 14px;
            opacity: 0.8;
            background: rgba(255,255,255,0.1);
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
        }

        /* Main Container */
        .container {
            padding: 0;
        }

        /* Quick Stats */
        .quick-stats {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: -30px 40px 30px 40px;
            position: relative;
            z-index: 3;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            text-align: center;
            flex: 1;
            border: 1px solid #e2e8f0;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 0 40px 30px 40px;
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Meta Information */
        .meta-section {
            background: #f8fafc;
            border-radius: 16px;
            padding: 30px;
            border: 1px solid #e2e8f0;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .meta-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .meta-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .meta-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 14px;
        }

        .meta-value {
            font-size: 14px;
            color: #1a202c;
            font-weight: 500;
            text-align: right;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-published {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        .status-draft {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            color: white;
        }

        /* Thumbnail Section */
        .thumbnail-section {
            background: white;
            border-radius: 16px;
            padding: 25px;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .thumbnail-container {
            margin: 15px 0;
            padding: 20px;
            background: #f7fafc;
            border-radius: 12px;
            border: 2px dashed #cbd5e0;
        }

        .thumbnail-placeholder {
            padding: 40px 20px;
            color: #a0aec0;
            font-style: italic;
            background: #edf2f7;
            border-radius: 8px;
        }

        .thumbnail-img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        /* Tags Section */
        .tags-section {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 16px;
            margin: 0 40px 30px 40px;
        }

        .tags-title {
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .tag {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 16px;
            margin: 5px 8px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid rgba(255,255,255,0.3);
        }

        /* Content Section */
        .content-section {
            background: white;
            border-radius: 16px;
            padding: 40px;
            margin: 0 40px 40px 40px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .content-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .content-title {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .content-subtitle {
            color: #718096;
            font-size: 16px;
        }

        .content-body {
            line-height: 1.8;
            font-size: 15px;
            color: #4a5568;
        }

        .content-body h1 {
            font-size: 22px;
            color: #2d3748;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }

        .content-body h2 {
            font-size: 20px;
            color: #2d3748;
            margin: 25px 0 15px 0;
        }

        .content-body h3 {
            font-size: 18px;
            color: #2d3748;
            margin: 20px 0 12px 0;
        }

        .content-body p {
            margin-bottom: 20px;
            text-align: justify;
        }

        .content-body img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }

        .content-body blockquote {
            border-left: 4px solid #667eea;
            background: #f8fafc;
            padding: 20px 25px;
            margin: 25px 0;
            border-radius: 0 12px 12px 0;
            font-style: italic;
            color: #4a5568;
        }

        .content-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            border-radius: 12px;
            overflow: hidden;
        }

        .content-body table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        .content-body table td {
            padding: 12px 20px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        .content-body table tr:nth-child(even) {
            background: #f8fafc;
        }

        /* Footer */
        .footer {
            background: #2d3748;
            color: white;
            padding: 30px 40px;
            text-align: center;
        }

        .footer-content {
            max-width: 600px;
            margin: 0 auto;
        }

        .footer-logo {
            font-weight: 700;
            color: #667eea;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .footer-meta {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #4a5568;
        }

        /* Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mb-4 { margin-bottom: 20px; }
        .mt-4 { margin-top: 20px; }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(102, 126, 234, 0.03);
            font-weight: 900;
            z-index: -1;
            pointer-events: none;
            font-family: 'DejaVu Sans', sans-serif;
        }

        /* Print Styles */
        @media print {
            .header {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
            }
            .stat-card {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
            }
            .tags-section {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
            }
            .watermark {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">{{ config('app.name') }}</div>

    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <h1>{{ $post->title }}</h1>
            <div class="header-subtitle">Dokumen Ekspor Berita Resmi</div>
            <div class="header-meta">
                {{ now()->format('d F Y, H:i') }} • {{ config('app.name') }}
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-number">#{{ $post->id }}</div>
            <div class="stat-label">ID Berita</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($post->views) }}</div>
            <div class="stat-label">Total Views</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $post->tags->count() }}</div>
            <div class="stat-label">Total Tags</div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Meta Information -->
        <div class="meta-section">
            <div class="section-title">Informasi Berita</div>
            <div class="meta-item">
                <span class="meta-label">Kategori</span>
                <span class="meta-value">{{ $post->category->name ?? 'Tidak ada kategori' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Penulis</span>
                <span class="meta-value">{{ $post->user->name }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Status</span>
                <span class="meta-value">
                    <span class="status-badge {{ $post->status ? 'status-published' : 'status-draft' }}">
                        {{ $post->status ? 'TERPUBLIKASI' : 'DRAFT' }}
                    </span>
                </span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Slug URL</span>
                <span class="meta-value">{{ $post->slug }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Dibuat</span>
                <span class="meta-value">{{ $post->created_at->format('d F Y, H:i') }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Diupdate</span>
                <span class="meta-value">{{ $post->updated_at->format('d F Y, H:i') }}</span>
            </div>
        </div>

        <!-- Thumbnail -->
        <div class="thumbnail-section">
            <div class="section-title">Thumbnail</div>
            <div class="thumbnail-container">
                @if($post->thumbnail && file_exists(public_path($post->thumbnail)))
                    <img src="{{ public_path($post->thumbnail) }}" 
                         alt="{{ $post->title }}" 
                         class="thumbnail-img">
                @else
                    <div class="thumbnail-placeholder">
                        <div>📷</div>
                        <div style="margin-top: 10px;">Tidak ada thumbnail tersedia</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tags -->
    @if($post->tags->count() > 0)
    <div class="tags-section">
        <div class="tags-title">Tags Berita</div>
        <div>
            @foreach($post->tags as $tag)
                <span class="tag">#{{ $tag->name }}</span>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Content -->
    <div class="content-section">
        <div class="content-header">
            <div class="content-title">Konten Berita Lengkap</div>
            <div class="content-subtitle">Dokumen asli dari sistem {{ config('app.name') }}</div>
        </div>
        <div class="content-body">
            {!! $post->content !!}
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-content">
            <div class="footer-logo">{{ config('app.name') }}</div>
            <div style="opacity: 0.9; margin-bottom: 15px;">
                Dokumen ini dibuat secara otomatis dan bersifat resmi
            </div>
            <div class="footer-meta">
                © {{ date('Y') }} {{ config('app.name') }} • All rights reserved<br>
                Document ID: {{ $post->id }}-{{ now()->format('YmdHis') }} • Generated on: {{ now()->format('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>
</body>