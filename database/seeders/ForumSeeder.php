<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ForumCategory;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'General Discussion',
                'slug' => 'general-discussion',
                'description' => 'Talk about anything related to the community.',
                'icon' => 'fas fa-comments',
                'order' => 1,
            ],
            [
                'name' => 'Game Reviews',
                'slug' => 'game-reviews',
                'description' => 'Share your thoughts on the latest games.',
                'icon' => 'fas fa-gamepad',
                'order' => 2,
            ],
            [
                'name' => 'Tutorials & Guides',
                'slug' => 'tutorials-guides',
                'description' => 'Help others by sharing your knowledge.',
                'icon' => 'fas fa-book',
                'order' => 3,
            ],
            [
                'name' => 'Server Events',
                'slug' => 'server-events',
                'description' => 'Discuss upcoming and past events.',
                'icon' => 'fas fa-calendar-alt',
                'order' => 4,
            ],
            [
                'name' => 'Off-Topic',
                'slug' => 'off-topic',
                'description' => 'Anything else goes here.',
                'icon' => 'fas fa-coffee',
                'order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            ForumCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
