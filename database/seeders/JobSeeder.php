<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'name' => 'Warrior',
                'slug' => 'warrior',
                'description' => 'Petarung tangguh yang aktif di voice chat. Mendapatkan bonus XP lebih besar dari aktivitas voice.',
                'icon' => 'fas fa-shield-alt',
                'color' => 'red',
                'passive_skill_name' => 'Voice Commander',
                'passive_skill_description' => 'Mendapatkan bonus XP 20% dari aktivitas voice chat.',
                'bonus_type' => 'xp_voice',
                'bonus_percentage' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Explorer',
                'slug' => 'explorer',
                'description' => 'Penjelajah yang suka berinteraksi di chat. Mendapatkan bonus XP lebih besar dari pesan teks.',
                'icon' => 'fas fa-compass',
                'color' => 'green',
                'passive_skill_name' => 'Chat Master',
                'passive_skill_description' => 'Mendapatkan bonus XP 20% dari aktivitas chat.',
                'bonus_type' => 'xp_message',
                'bonus_percentage' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Scholar',
                'slug' => 'scholar',
                'description' => 'Cendekiawan yang aktif di event dan quiz. Mendapatkan bonus XP lebih besar dari event.',
                'icon' => 'fas fa-book',
                'color' => 'blue',
                'passive_skill_name' => 'Event Strategist',
                'passive_skill_description' => 'Mendapatkan bonus XP 20% dari kemenangan event.',
                'bonus_type' => 'xp_event',
                'bonus_percentage' => 20,
                'is_active' => true,
            ],
        ];

        foreach ($jobs as $job) {
            \App\Models\Job::updateOrCreate(
                ['slug' => $job['slug']],
                $job
            );
        }
    }
}
