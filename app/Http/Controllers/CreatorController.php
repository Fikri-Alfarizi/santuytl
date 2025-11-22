<?php

namespace App\Http\Controllers;

use App\Models\CreatorApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreatorController extends Controller
{
    public function index()
    {
        $application = null;
        if (Auth::check()) {
            $application = CreatorApplication::where('user_id', Auth::id())->latest()->first();
        }
        return view('creators.index', compact('application'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform' => 'required|string|in:YouTube,Twitch,TikTok',
            'channel_url' => 'required|url',
            'subscriber_count' => 'required|integer|min:0',
        ]);

        // Check if already has pending application
        $existing = CreatorApplication::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending application.');
        }

        CreatorApplication::create([
            'user_id' => Auth::id(),
            'platform' => $request->platform,
            'channel_url' => $request->channel_url,
            'subscriber_count' => $request->subscriber_count,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Application submitted successfully!');
    }

    // Admin methods
    public function adminIndex()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized.');
        }

        $applications = CreatorApplication::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.creators.index', compact('applications'));
    }

    public function approve($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized.');
        }

        $application = CreatorApplication::findOrFail($id);
        $application->update(['status' => 'approved']);
        
        // Assign Creator Role or Badge here if needed
        // e.g., $application->user->assignRole('creator');

        return back()->with('success', 'Application approved.');
    }

    public function reject($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized.');
        }

        $application = CreatorApplication::findOrFail($id);
        $application->update(['status' => 'rejected']);

        return back()->with('success', 'Application rejected.');
    }
}
