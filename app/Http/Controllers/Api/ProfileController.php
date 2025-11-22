<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::with('userStat')->findOrFail($id);
        return view('profile.show', compact('user'));
    }
}
