<?php
// app/Http/Controllers/Api/GameEventSyncController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Event;

class GameEventSyncController extends Controller
{
    // Endpoint untuk menerima data game dari bot Discord
    public function storeGame(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'link' => 'required|string',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'discord_message_id' => 'nullable|string',
        ]);
        $game = Game::updateOrCreate(
            ['discord_message_id' => $data['discord_message_id']],
            $data
        );
        return response()->json(['success' => true, 'game' => $game]);
    }

    // Endpoint untuk menerima data event dari bot Discord
    public function storeEvent(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'date' => 'nullable|string',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
            'discord_message_id' => 'nullable|string',
        ]);
        
        // Generate slug from title
        $slug = \Str::slug($data['title']);
        $originalSlug = $slug;
        $counter = 1;
        
        // Ensure unique slug
        while (Event::where('slug', $slug)->where('discord_message_id', '!=', $data['discord_message_id'])->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        // Parse date if provided
        $parsedDate = null;
        $endsAt = null;
        if (!empty($data['date'])) {
            try {
                $parsedDate = \Carbon\Carbon::parse($data['date']);
                $endsAt = $parsedDate->copy()->addDays(7); // Default 7 days duration
            } catch (\Exception $e) {
                // If parsing fails, leave as null
            }
        }
        
        $event = Event::updateOrCreate(
            ['discord_message_id' => $data['discord_message_id']],
            [
                'title' => $data['title'],
                'slug' => $slug,
                'description' => $data['description'] ?? '',
                'banner_image' => $data['image'] ?? null,
                'type' => 'custom',
                'access_level' => 'all',
                'is_active' => true,
                'starts_at' => $parsedDate,
                'ends_at' => $endsAt,
            ]
        );
        
        return response()->json(['success' => true, 'event' => $event]);
    }
}
