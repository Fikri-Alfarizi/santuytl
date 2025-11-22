<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;

class TutorialController extends Controller
{
    public function index()
    {
        $tutorials = Tutorial::latest()->paginate(20);
        return view('tutorial.index', compact('tutorials'));
    }
}
