<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ReactionTestController extends Controller
{
    public function submit()
    {
        // Implementasi logika reaction test
        return response()->json(['result' => 'ok']);
    }
}
