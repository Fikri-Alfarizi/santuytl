<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Strike;

class StrikeController extends Controller
{
    public function index()
    {
        $strikes = Strike::latest()->paginate(20);
        return view('strike.index', compact('strikes'));
    }
    public function give()
    {
        // Implementasi pemberian strike
        return response()->json(['result' => 'ok']);
    }
}
