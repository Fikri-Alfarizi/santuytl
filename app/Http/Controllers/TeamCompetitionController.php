<?php

namespace App\Http\Controllers;

use App\Models\TeamCompetition;
use App\Models\TeamCompetitionScore;
use Illuminate\Http\Request;

class TeamCompetitionController extends Controller
{
    public function index()
    {
        $competitions = TeamCompetition::with('scores.team')->orderByDesc('start_at')->get();
        return view('team_competition.index', compact('competitions'));
    }

    public function show($id)
    {
        $competition = TeamCompetition::with('scores.team')->findOrFail($id);
        return view('team_competition.show', compact('competition'));
    }
}
