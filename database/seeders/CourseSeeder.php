<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseChapter;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::firstOrCreate([
            'slug' => 'discord-community-mastery',
        ], [
            'title' => 'Discord Community Mastery',
            'description' => 'Learn how to build, manage, and grow a thriving Discord community. This course covers everything from server setup to advanced bot integration.',
            'thumbnail' => null,
            'xp_reward' => 500,
            'coin_reward' => 100,
            'is_active' => true,
        ]);

        $chapters = [
            [
                'title' => 'Introduction to Discord',
                'content' => "Welcome to the course! In this chapter, we'll cover the basics of Discord.\n\n### What is Discord?\nDiscord is a VoIP and instant messaging social platform. Users have the ability to communicate with voice calls, video calls, text messaging, media and files in private chats or as part of communities called \"servers\".\n\n### Why use it?\nIt's great for gaming, study groups, and hobby communities.",
                'video_url' => null,
                'order' => 1,
            ],
            [
                'title' => 'Setting Up Your Server',
                'content' => "Now let's create your first server.\n\n1. Click the '+' icon on the left sidebar.\n2. Select 'Create My Own'.\n3. Choose 'For me and my friends' or 'For a club or community'.\n4. Give it a name and icon.\n\nCongratulations! You have a server.",
                'video_url' => null,
                'order' => 2,
            ],
            [
                'title' => 'Roles and Permissions',
                'content' => "Roles are crucial for managing your community.\n\n- **Admin**: Full access.\n- **Moderator**: Can kick/ban and manage messages.\n- **Member**: Standard access.\n\nGo to Server Settings > Roles to create them.",
                'video_url' => null,
                'order' => 3,
            ],
            [
                'title' => 'Adding Bots',
                'content' => "Bots automate tasks and add fun features.\n\nPopular bots:\n- **MEE6**: Moderation and leveling.\n- **Dyno**: Multi-purpose.\n- **Groovy/Hydra**: Music (RIP).\n\nTo add a bot, visit their website and click 'Invite'.",
                'video_url' => null,
                'order' => 4,
            ],
        ];

        foreach ($chapters as $chapterData) {
            CourseChapter::firstOrCreate(
                [
                    'course_id' => $course->id,
                    'title' => $chapterData['title'],
                ],
                array_merge($chapterData, ['course_id' => $course->id])
            );
        }
    }
}
