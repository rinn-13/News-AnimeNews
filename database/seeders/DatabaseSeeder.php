<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User Seeder
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@news.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Editor',
            'email' => 'editor@news.com',
            'password' => Hash::make('password'),
            'role' => 'editor',
        ]);

        // Categories - URUT BERDASARKAN NAMA
        $categories = [
            'Ekonomi', 'Hiburan', 'Kesehatan', 'Olahraga', 'Politik', 'Teknologi'
        ];

        $categoryIds = [];
        foreach ($categories as $category) {
            $cat = Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
            $categoryIds[] = $cat->id;
        }

        // Tags - URUT BERDASARKAN NAMA
        $tags = [
            'Analysis', 'Breaking News', 'International', 'Local', 'Trending', 'Viral'
        ];

        $tagIds = [];
        foreach ($tags as $tag) {
            $tagModel = Tag::create([
                'name' => $tag,
                'slug' => Str::slug($tag),
            ]);
            $tagIds[] = $tagModel->id;
        }

        // Posts dengan status yang bervariasi
        $posts = [
            [
                'title' => 'Perkembangan Teknologi AI di Indonesia Tahun 2024',
                'content' => 'Artificial Intelligence (AI) semakin berkembang pesat di Indonesia. Banyak startup lokal yang mulai mengadopsi teknologi ini untuk meningkatkan efisiensi bisnis mereka. Pemerintah juga mendukung pengembangan ekosistem AI melalui berbagai program dan insentif. Dalam beberapa tahun terakhir, investasi di sektor AI meningkat signifikan, menandakan potensi besar yang dimiliki Indonesia.',
                'category_id' => $categoryIds[5], // Teknologi
                'user_id' => 1,
                'status' => true,
                'views' => 150,
            ],
            [
                'title' => 'Pertumbuhan Ekonomi Kuartal III Meningkat Signifikan',
                'content' => 'Badan Pusat Statistik melaporkan pertumbuhan ekonomi Indonesia pada kuartal III mencapai 5.1%. Peningkatan ini didorong oleh konsumsi rumah tangga dan investasi yang tetap kuat meskipun tantangan global. Sektor manufaktur dan jasa menunjukkan performa yang positif, memberikan kontribusi besar terhadap pertumbuhan ekonomi nasional.',
                'category_id' => $categoryIds[0], // Ekonomi
                'user_id' => 2,
                'status' => true,
                'views' => 89,
            ],
            [
                'title' => 'Timnas Indonesia Sukses di Ajang Internasional',
                'content' => 'Tim nasional Indonesia berhasil meraih kemenangan penting dalam pertandingan kualifikasi. Performa pemain muda menjadi sorotan dan memberikan harapan untuk masa depan sepak bola Indonesia. Pelatih tim menyatakan kebanggaannya atas pencapaian ini dan berkomitmen untuk terus mengembangkan bakat-bakat muda.',
                'category_id' => $categoryIds[3], // Olahraga
                'user_id' => 1,
                'status' => true,
                'views' => 234,
            ],
            [
                'title' => 'Konser Musik Besar Akan Digelar di Jakarta',
                'content' => 'Event musik terbesar tahun ini akan menghadirkan berbagai artis ternama dari dalam dan luar negeri. Tiket sudah bisa dipesan secara online melalui platform resmi. Panitia menyiapkan berbagai fasilitas terbaik untuk memastikan pengalaman yang tak terlupakan bagi penonton.',
                'category_id' => $categoryIds[1], // Hiburan
                'user_id' => 2,
                'status' => false, // Draft
                'views' => 45,
            ],
            [
                'title' => 'Inovasi Terbaru dalam Penanganan Penyakit Kronis',
                'content' => 'Para peneliti berhasil menemukan metode baru untuk penanganan penyakit kronis. Temuan ini diharapkan dapat meningkatkan kualitas hidup pasien dan mengurangi biaya pengobatan. Penelitian ini telah melalui berbagai tahap uji klinis dan menunjukkan hasil yang menjanjikan.',
                'category_id' => $categoryIds[2], // Kesehatan
                'user_id' => 1,
                'status' => true,
                'views' => 167,
            ],
            [
                'title' => 'Kebijakan Baru Pemerintah dalam Sektor Energi',
                'content' => 'Pemerintah mengumumkan kebijakan baru untuk mendorong transisi energi bersih. Kebijakan ini diharapkan dapat menarik investasi dan menciptakan lapangan kerja baru di sektor energi terbarukan. Langkah ini sejalan dengan komitmen Indonesia dalam mengurangi emisi karbon.',
                'category_id' => $categoryIds[4], // Politik
                'user_id' => 2,
                'status' => true,
                'views' => 98,
            ],
            [
                'title' => 'Startup Teknologi Pendidikan Raih Pendanaan Besar',
                'content' => 'Startup lokal di bidang teknologi pendidikan berhasil mengumpulkan pendanaan seri B senilai jutaan dolar. Pendanaan ini akan digunakan untuk ekspansi pasar dan pengembangan produk. Ini membuktikan bahwa ekosistem startup Indonesia terus berkembang dengan pesat.',
                'category_id' => $categoryIds[5], // Teknologi
                'user_id' => 1,
                'status' => true,
                'views' => 123,
            ],
            [
                'title' => 'Festival Kuliner Nusantara Kembali Digelar',
                'content' => 'Setelah hiatus dua tahun, festival kuliner nusantara kembali digelar dengan menghadirkan berbagai makanan tradisional dari seluruh Indonesia. Event ini diharapkan dapat mempromosikan kekayaan kuliner Indonesia sekaligus mendukung UMKM lokal.',
                'category_id' => $categoryIds[1], // Hiburan
                'user_id' => 2,
                'status' => true,
                'views' => 76,
            ]
        ];

        foreach ($posts as $postData) {
            $post = Post::create([
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'content' => $postData['content'],
                'category_id' => $postData['category_id'],
                'user_id' => $postData['user_id'],
                'status' => $postData['status'],
                'views' => $postData['views'],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 10)),
            ]);

            // Attach random tags (2-3 tags per post) - FIXED METHOD
            $randomTagCount = rand(2, 3);
            $randomTags = collect($tagIds)->random($randomTagCount)->all();
            $post->tags()->attach($randomTags);
        }

        // Tambahkan beberapa post dengan views tinggi untuk testing berita populer
        $popularPosts = [
            [
                'title' => 'Viral: Inovasi Teknologi yang Mengubah Gaya Hidup',
                'content' => 'Sebuah inovasi teknologi baru berhasil viral di media sosial karena kemampuannya yang revolusioner dalam mengubah gaya hidup masyarakat. Teknologi ini telah digunakan oleh jutaan orang dalam waktu singkat.',
                'category_id' => $categoryIds[5],
                'user_id' => 1,
                'status' => true,
                'views' => 1000,
            ],
            [
                'title' => 'Breaking: Pengumuman Penting dari Pemerintah',
                'content' => 'Pemerintah mengumumkan kebijakan penting yang akan mempengaruhi berbagai sektor. Pengumuman ini ditunggu-tunggu oleh banyak pihak dan diharapkan dapat membawa dampak positif.',
                'category_id' => $categoryIds[4],
                'user_id' => 2,
                'status' => true,
                'views' => 850,
            ]
        ];

        foreach ($popularPosts as $postData) {
            $post = Post::create([
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'content' => $postData['content'],
                'category_id' => $postData['category_id'],
                'user_id' => $postData['user_id'],
                'status' => $postData['status'],
                'views' => $postData['views'],
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(rand(1, 12)),
            ]);

            $randomTagCount = rand(2, 3);
            $randomTags = collect($tagIds)->random($randomTagCount)->all();
            $post->tags()->attach($randomTags);
        }
    }
}