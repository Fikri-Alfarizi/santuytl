<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil ID admin sebagai penulis
        $authorId = DB::table('users')->where('email', 'admin@gamehub.com')->value('id');

        $posts = [
            [
                'title' => '5 Game Indie Terbaik yang Wajib Kamu Coba Tahun Ini',
                'slug' => '5-game-indie-terbaik-wajib-coba',
                'excerpt' => 'Tahun ini dipenuhi dengan game-game indie yang kreatif dan inovatif. Berikut adalah 5 pilihan terbaik yang tidak boleh Anda lewatkan.',
                'content' => '<p>Dunia game indie terus berkembang dengan pesat, menawarkan pengalaman bermain yang unik dan sering kali jauh dari mainstream. Tahun ini saja, kita sudah disuguhi berbagai judul yang memukau, baik dari segi gameplay, cerita, maupun visualisasi.</p><p>Dari petualangan emosional hingga teka-teki yang memutar otak, berikut adalah 5 game indie terbaik yang wajib kamu coba...</p>',
                'featured_image' => 'https://via.placeholder.com/1200x630/1e3a8a/ffffff?text=5+Game+Indie',
                'status' => 'published',
                'is_featured' => true,
                'meta_title' => '5 Game Indie Terbaik 2023',
                'meta_description' => 'Daftar game indie terbaik dan wajib dimainkan tahun ini.',
                'author_id' => $authorId,
                'categories' => json_encode(['Artikel', 'Rekomendasi']),
                'tags' => json_encode(['indie', 'game', '2023']),
                'published_at' => now()->subDays(5),
                'posted_to_discord' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Panduan Lengkap: Cara Mengatasi Lag Saat Bermain Game',
                'slug' => 'panduan-cara-mengatasi-lag-saat-bermain-game',
                'excerpt' => 'Lag adalah musuh utama setiap gamer. Simak panduan lengkap kami untuk mengidentifikasi dan mengatasi lag agar pengalaman bermain Anda lebih lancar.',
                'content' => '<p>Apakah ada yang lebih menjengkelkan daripada lag di tengah pertandingan yang sengit? Stutter, frame rate drop, dan koneksi yang tidak stabil bisa merusak semua kesenangan.</p><p>Tapi jangan khawatir, dalam panduan ini, kami akan membahas penyebab umum lag dan memberikan solusi praktis, mulai dari pengaturan dalam game hingga optimasi perangkat keras lunak...</p>',
                'featured_image' => 'https://via.placeholder.com/1200x630/722f37/ffffff?text=Panduan+Anti+Lag',
                'status' => 'published',
                'is_featured' => false,
                'meta_title' => 'Cara Mengatasi Lag Game',
                'meta_description' => 'Solusi dan tips untuk mengatasi lag saat bermain game di PC.',
                'author_id' => $authorId,
                'categories' => json_encode(['Tutorial', 'Teknologi']),
                'tags' => json_encode(['lag', 'fps', 'optimasi', 'pc']),
                'published_at' => now()->subDays(12),
                'posted_to_discord' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('blog_posts')->insert($posts);
    }
}