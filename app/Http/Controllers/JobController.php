<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use Carbon\Carbon;

class JobController extends Controller
{
    /**
     * Show available jobs
     */
    public function index()
    {
        $user = Auth::user();
        $stats = $user->stats;
        
        if (!$stats) {
            $stats = $user->stats()->create([
                'xp' => 0, 'level' => 1, 'xp_to_next_level' => 100
            ]);
        }

        $jobs = Job::active()->get();
        $currentJob = $stats->job;

        return view('jobs.index', compact('user', 'stats', 'jobs', 'currentJob'));
    }

    /**
     * Select a job (for the first time or free change if allowed)
     */
    public function select(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
        ]);

        $user = Auth::user();
        $stats = $user->stats;
        $job = Job::findOrFail($request->job_id);

        // If user already has a job, redirect to change method or handle differently
        // For now, we assume this is for initial selection or free selection
        if ($stats->job_id) {
            return $this->change($request);
        }

        $stats->job_id = $job->id;
        $stats->save();

        return redirect()->route('jobs.index')->with('success', "Selamat! Anda telah memilih job {$job->name}.");
    }

    /**
     * Change job (with cost/cooldown logic)
     */
    public function change(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
        ]);

        $user = Auth::user();
        $stats = $user->stats;
        $newJob = Job::findOrFail($request->job_id);

        if ($stats->job_id == $newJob->id) {
            return back()->with('error', 'Anda sudah memiliki job ini.');
        }

        // Check cooldown and cost from config
        $changeCost = config('gamification.jobs.change_cost_coins', 500);
        
        // Check if user has enough coins (assuming coins column exists in user_stats)
        if ($stats->coins < $changeCost) {
            return back()->with('error', "Koin tidak cukup. Biaya ganti job: {$changeCost} koin.");
        }

        // Deduct coins
        $stats->coins -= $changeCost;
        $stats->job_id = $newJob->id;
        $stats->save();

        return redirect()->route('jobs.index')->with('success', "Anda berhasil berganti job menjadi {$newJob->name}. Biaya: {$changeCost} koin.");
    }
}
