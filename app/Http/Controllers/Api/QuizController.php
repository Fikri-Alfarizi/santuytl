<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class QuizController extends Controller
{
    public function answer()
    {
        // Implementasi logika quiz
        return response()->json(['result' => 'ok']);
    }
}
