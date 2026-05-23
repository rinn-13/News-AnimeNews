<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PostExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $postId;

    public function __construct($postId)
    {
        $this->postId = $postId;
    }

    public function collection()
    {
        return Post::where('id', $this->postId)->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Judul',
            'Konten',
            'Kategori',
            'Tags',
            'Penulis',
            'Status',
            'Views',
            'Thumbnail',
            'Dibuat Pada',
            'Diupdate Pada',
            'Slug'
        ];
    }

    public function map($post): array
    {
        return [
            $post->id,
            $post->title,
            strip_tags($post->content),
            $post->category->name,
            $post->tags->pluck('name')->implode(', '),
            $post->user->name,
            $post->status ? 'Aktif' : 'Nonaktif',
            $post->views,
            $post->thumbnail ? asset($post->thumbnail) : 'Tidak ada',
            $post->created_at ? $post->created_at->format('d-m-Y H:i:s') : 'N/A',
            $post->updated_at ? $post->updated_at->format('d-m-Y H:i:s') : 'N/A',
            $post->slug
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A' => ['width' => 10],
            'B' => ['width' => 30],
            'C' => ['width' => 50],
            'D' => ['width' => 20],
            'E' => ['width' => 25],
            'F' => ['width' => 20],
            'G' => ['width' => 15],
            'H' => ['width' => 10],
            'I' => ['width' => 30],
            'J' => ['width' => 20],
            'K' => ['width' => 20],
            'L' => ['width' => 25],
        ];
    }
}