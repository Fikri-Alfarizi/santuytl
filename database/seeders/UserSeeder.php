<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil ID badge "Pemula" dan "VIP"
        $pemulaBadgeId = DB::table('badges')->where('slug', 'pemula')->value('id');
        $vipBadgeId = DB::table('badges')->where('slug', 'vip')->value('id');

        // Buat Admin
        $adminId = DB::table('users')->insertGetId([
            'username' => 'AdminGameHub',
            'email' => 'admin@gamehub.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'level' => 'sepuh',
            'vip_expires_at' => now()->addYears(10), // Admin selalu VIP
            // Discord fields
            'discord_id' => '100000000000000001',
            'discord_username' => 'AdminGameHub',
            'discord_discriminator' => '0001',
            'discord_avatar' => 'a_1b2c3d4e5f6g7h8i9j0k', // animated hash
            'avatar' => 'https://cdn.discordapp.com/avatars/100000000000000001/a_1b2c3d4e5f6g7h8i9j0k.gif?size=256',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Statistik Admin
        DB::table('user_stats')->insert([
            'user_id' => $adminId,
            'xp' => 5000,
            'level' => 5,
            'xp_to_next_level' => 0,
            'messages_count' => 500,
            'games_downloaded' => 50,
            'requests_made' => 0,
            'events_participated' => 10,
            'events_won' => 3,
            'last_active_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Berikan badge ke Admin
        DB::table('user_badges')->insert([
            ['user_id' => $adminId, 'badge_id' => $pemulaBadgeId, 'earned_at' => now()],
            ['user_id' => $adminId, 'badge_id' => $vipBadgeId, 'earned_at' => now()],
        ]);


        // Buat User VIP
        $vipUserId = DB::table('users')->insertGetId([
            'username' => 'AnggaSetiawan',
            'email' => 'vip@gamehub.com',
            'password' => Hash::make('password'),
            'role' => 'vip',
            'level' => 'suhu',
            'vip_expires_at' => now()->addDays(90),
            // Discord fields
            'discord_id' => '100000000000000002',
            'discord_username' => 'AnggaSetiawan',
            'discord_discriminator' => '1234',
            'discord_avatar' => 'b2c3d4e5f6g7h8i9j0k1',
            'avatar' => 'https://cdn.discordapp.com/avatars/100000000000000002/b2c3d4e5f6g7h8i9j0k1.png?size=256',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Statistik User VIP
        DB::table('user_stats')->insert([
            'user_id' => $vipUserId,
            'xp' => 1500,
            'level' => 4,
            'xp_to_next_level' => 500,
            'messages_count' => 150,
            'games_downloaded' => 25,
            'requests_made' => 5,
            'events_participated' => 4,
            'events_won' => 1,
            'last_active_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Berikan badge ke User VIP
        DB::table('user_badges')->insert([
            ['user_id' => $vipUserId, 'badge_id' => $pemulaBadgeId, 'earned_at' => now()],
            ['user_id' => $vipUserId, 'badge_id' => $vipBadgeId, 'earned_at' => now()],
        ]);


        // Buat User Member Biasa
        $memberUserId = DB::table('users')->insertGetId([
            'username' => 'BudiSantoso',
            'email' => 'member@gamehub.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'level' => 'pemula',
            // Discord fields
            'discord_id' => '100000000000000003',
            'discord_username' => 'BudiSantoso',
            'discord_discriminator' => '5678',
            'discord_avatar' => 'c3d4e5f6g7h8i9j0k1b2',
            'avatar' => 'https://cdn.discordapp.com/avatars/100000000000000003/c3d4e5f6g7h8i9j0k1b2.png?size=256',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Statistik User Member
        DB::table('user_stats')->insert([
            'user_id' => $memberUserId,
            'xp' => 50,
            'level' => 2,
            'xp_to_next_level' => 200,
            'messages_count' => 20,
            'games_downloaded' => 5,
            'requests_made' => 0,
            'events_participated' => 1,
            'events_won' => 0,
            'last_active_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Berikan badge ke User Member
        DB::table('user_badges')->insert([
            'user_id' => $memberUserId, 'badge_id' => $pemulaBadgeId, 'earned_at' => now()
        ]);
    }
}