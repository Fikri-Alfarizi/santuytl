<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::orderBy('start_date', 'desc')->paginate(10);
        return view('competitions.index', compact('competitions'));
    }

    public function show(Competition $competition)
    {
        $competition->load('participants.team', 'participants.user');
        return view('competitions.show', compact('competition'));
    }
}
