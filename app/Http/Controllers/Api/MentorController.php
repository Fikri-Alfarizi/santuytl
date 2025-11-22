<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mentor;

class MentorController extends Controller
{
    public function book()
    {
        // Implementasi booking mentor
        return response()->json(['result' => 'ok']);
    }
    public function rate()
    {
        // Implementasi rating mentor
        return response()->json(['result' => 'ok']);
    }
}
