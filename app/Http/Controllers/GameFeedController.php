<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GameFeedController extends Controller
{
    public function index()
    {
        // Cache the feed for 1 hour to avoid hitting rate limits
        $news = Cache::remember('steam_news_feed', 3600, function () {
            try {
                // Fetch news for a popular game (e.g., CS2 appid 730, or Dota 2 appid 570)
                // Alternatively, fetch featured news if available via another endpoint
                // For this example, we'll fetch news for CS2 (730) and Dota 2 (570) and merge them
                
                $cs2News = Http::get('http://api.steampowered.com/ISteamNews/GetNewsForApp/v0002/?appid=730&count=5&maxlength=300&format=json')->json();
                $dota2News = Http::get('http://api.steampowered.com/ISteamNews/GetNewsForApp/v0002/?appid=570&count=5&maxlength=300&format=json')->json();

                $feed = collect([]);
                
                if (isset($cs2News['appnews']['newsitems'])) {
                    $feed = $feed->merge($cs2News['appnews']['newsitems']);
                }
                
                if (isset($dota2News['appnews']['newsitems'])) {
                    $feed = $feed->merge($dota2News['appnews']['newsitems']);
                }

                return $feed->sortByDesc('date')->values()->all();

            } catch (\Exception $e) {
                return [];
            }
        });

        return view('games.index', compact('news'));
    }
}
