<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PanelController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.panel', compact('user'));
    }
}
