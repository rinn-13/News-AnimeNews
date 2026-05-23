<!DOCTYPE html>
<html>
<head>
    <title>{{ $post->title }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, Arial, sans-serif; 
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 18px;
        }
        .meta-info {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }
        .meta-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-info td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }
        .meta-info .label {
            font-weight: bold;
            width: 30%;
            color: #2c3e50;
        }
        .thumbnail {
            text-align: center;
            margin-bottom: 15px;
        }
        .thumbnail img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .content {
            margin-top: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #fff;
        }
        .tags {
            margin: 15px 0;
            padding: 10px;
            background: #e9ecef;
            border-radius: 5px;
        }
        .tag {
            display: inline-block;
            background: #6c757d;
            color: white;
            padding: 3px 6px;
            margin: 1px;
            border-radius: 3px;
            font-size: 10px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #7f8c8d;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-primary { background: #007bff; color: white; }
        .badge-success { background: #28a745; color: white; }
        .badge-danger { background: #dc3545; color: white; }
        
        /* Style untuk konten HTML */
        .content-body {
            line-height: 1.5;
        }
        .content-body p {
            margin-bottom: 0.8em;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $post->title }}</h1>
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td class="label">Judul Berita:</td>
                <td><strong>{{ $post->title }}</strong></td>
            </tr>
            <tr>
                <td class="label">Kategori:</td>
                <td>
                    <span class="badge badge-primary">{{ $post->category->name }}</span>
                </td>
            </tr>
            <tr>
                <td class="label">Penulis:</td>
                <td>{{ $post->user->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Publikasi:</td>
                <td>{{ $post->created_at ? $post->created_at->format('d F Y H:i') : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Update:</td>
                <td>{{ $post->updated_at ? $post->updated_at->format('d F Y H:i') : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Status:</td>
                <td>
                    <span class="badge {{ $post->status ? 'badge-success' : 'badge-danger' }}">
                        {{ $post->status ? 'PUBLIK' : 'DRAFT' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Total Views:</td>
                <td>{{ number_format($post->views) }}</td>
            </tr>
        </table>
    </div>

    @if($post->tags && $post->tags->count() > 0)
    <div class="tags">
        <p><strong>Tags:</strong></p>
        @foreach($post->tags as $tag)
            <span class="tag">{{ $tag->name }}</span>
        @endforeach
    </div>
    @endif

    <div class="content">
        <h3>Konten Berita:</h3>
        <div class="content-body">
            {!! $post->content !!}
        </div>
    </div>

    <div class="footer">
        <p>Dicetak dari Sistem CMS Berita &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>