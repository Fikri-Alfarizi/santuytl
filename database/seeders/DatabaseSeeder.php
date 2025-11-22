<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BadgeSeeder::class,
            UserSeeder::class,
            GameSeeder::class,
            BlogPostSeeder::class,
            EventSeeder::class,
            JobSeeder::class,
            ForumSeeder::class,
            CourseSeeder::class,
        ]);
    }
}