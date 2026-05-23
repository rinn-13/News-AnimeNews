<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'content', 'thumbnail', 'status', 'views', 'category_id', 'user_id',
    ];

    protected $attributes = [
        'views' => 0,
        'status' => true,
    ];

    // Accessor untuk thumbnail_url - SIMPLE VERSION
    protected $appends = ['thumbnail_url'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    // Accessor untuk thumbnail_url - PASTI BERFUNGSI
    public function getThumbnailUrlAttribute()
    {
        // Jika ada thumbnail dan file exists di public/thumbnails
        if ($this->thumbnail && file_exists(public_path($this->thumbnail))) {
            return asset($this->thumbnail);
        }
        
        return 'https://via.placeholder.com/800x400/4361ee/ffffff?text=No+Thumbnail';
    }

    // Method untuk cek apakah thumbnail ada
    public function hasThumbnail()
    {
        return $this->thumbnail && file_exists(public_path($this->thumbnail));
    }

    // Scope untuk berita terbaru
    public function scopeLatestPosts($query)
    {
        return $query->where('status', true)->orderBy('created_at', 'desc');
    }

    // Accessor untuk excerpt
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }
}