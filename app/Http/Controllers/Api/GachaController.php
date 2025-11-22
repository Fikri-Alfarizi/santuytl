<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class GachaController extends Controller
{
    public function spin()
    {
        // Implementasi logika gacha
        return response()->json(['result' => 'ok']);
    }
}
