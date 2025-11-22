<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StaffController extends Controller
{
    public function index()
    {
        try {
            $botApiUrl = env('DISCORD_BOT_API_URL', 'http://localhost:3000');
            $response = Http::get("$botApiUrl/staff");

            if ($response->successful()) {
                $staffMembers = $response->json()['staff'];
                return view('staff.index', compact('staffMembers'));
            } else {
                return view('staff.index', ['staffMembers' => collect(), 'error' => 'Failed to fetch staff from bot.']);
            }
        } catch (\Exception $e) {
            return view('staff.index', ['staffMembers' => collect(), 'error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}
