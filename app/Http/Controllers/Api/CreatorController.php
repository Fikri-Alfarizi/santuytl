<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CreatorProgram;

class CreatorController extends Controller
{
    public function feed()
    {
        $feed = CreatorProgram::where('status', 'approved')->latest()->paginate(20);
        return view('creator.index', compact('feed'));
    }
    public function submit()
    {
        // Implementasi submit konten
        return response()->json(['result' => 'ok']);
    }
}
