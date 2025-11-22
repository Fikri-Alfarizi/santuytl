<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::withCount('members')->orderBy('total_xp', 'desc')->paginate(10);
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:teams|max:255',
            'description' => 'nullable|string',
        ]);

        $team = Team::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'leader_id' => Auth::id(),
        ]);

        $team->members()->attach(Auth::id(), ['role' => 'leader']);

        return redirect()->route('teams.show', $team)->with('success', 'Team created successfully!');
    }

    public function show(Team $team)
    {
        $team->load('members', 'leader');
        return view('teams.show', compact('team'));
    }

    public function join(Team $team)
    {
        if ($team->members()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'You are already a member of this team.');
        }

        $userTeam = TeamMember::where('user_id', Auth::id())->first();
        if ($userTeam) {
             return back()->with('error', 'You are already in a team. Leave it first.');
        }

        $team->members()->attach(Auth::id());
        return back()->with('success', 'Joined team successfully!');
    }

    public function leave(Team $team)
    {
        if ($team->leader_id == Auth::id()) {
             return back()->with('error', 'Leader cannot leave the team. Disband it or transfer leadership.');
        }

        $team->members()->detach(Auth::id());
        return redirect()->route('teams.index')->with('success', 'Left team successfully.');
    }
}
