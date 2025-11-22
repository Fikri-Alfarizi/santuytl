<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class LuckyWheelController extends Controller
{
    public function spin()
    {
        // Implementasi logika lucky wheel
        return response()->json(['result' => 'ok']);
    }
}
