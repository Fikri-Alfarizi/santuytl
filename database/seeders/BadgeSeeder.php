<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $badges = [
            [
                'name' => 'Pemula',
                'slug' => 'pemula',
                'description' => 'Diberikan saat pertama kali bergabung.',
                'icon' => 'fa-user-plus',
                'color' => '#3498db',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pengunduh Setia',
                'slug' => 'pengunduh-setia',
                'description' => 'Mengunduh 10 game.',
                'icon' => 'fa-download',
                'color' => '#2ecc71',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pemenang Event',
                'slug' => 'pemenang-event',
                'description' => 'Memenangkan salah satu event komunitas.',
                'icon' => 'fa-trophy',
                'color' => '#f1c40f',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VIP',
                'slug' => 'vip',
                'description' => 'Status VIP sedang aktif.',
                'icon' => 'fa-crown',
                'color' => '#e74c3c',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sepuh',
                'slug' => 'sepuh',
                'description' => 'Mencapai level tertinggi di komunitas.',
                'icon' => 'fa-star',
                'color' => '#9b59b6',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('badges')->insert($badges);
    }
}