<!DOCTYPE html>
<html>
<head>
    <title>Daftar Berita</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daftar Berita</h1>
        <p>Total: {{ $posts->count() }} berita | Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Penulis</th>
                <th width="80">Status</th>
                <th width="60">Views</th>
                <th width="120">Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $index => $post)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ Str::limit($post->title, 50) }}</td>
                <td>{{ $post->category->name }}</td>
                <td>{{ $post->user->name }}</td>
                <td>{{ $post->status ? 'Aktif' : 'Nonaktif' }}</td>
                <td>{{ $post->views }}</td>
                <td>{{ $post->created_at ? $post->created_at->format('d-m-Y H:i') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak dari Sistem CMS Berita &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>