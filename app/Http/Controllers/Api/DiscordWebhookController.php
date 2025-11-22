<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscordWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Proses event dari Discord bot
        return response()->json(['status' => 'ok']);
    }
    public function addXp(Request $request)
    {
        // Tambah XP user dari Discord
        return response()->json(['status' => 'ok']);
    }
    public function addCoins(Request $request)
    {
        // Tambah koin user dari Discord
        return response()->json(['status' => 'ok']);
    }
}
