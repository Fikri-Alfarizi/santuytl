<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\EventVote;
use App\Models\UserStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of active events.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $events = Event::where('is_active', true)
            ->orderByRaw("FIELD(tier, 'daily', 'weekly', 'monthly', 'special')")
            ->orderBy('date', 'asc')
            ->get();

        return view('events.index', compact('events'));
    }

    /**
     * Display the specified event.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $user = Auth::user();
        $event = Event::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        $participation = null;
        if ($user) {
            $participation = EventParticipant::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->first();
        }
        $topParticipants = EventParticipant::where('event_id', $event->id)
            ->with('user')
            ->orderBy('votes', 'desc')
            ->take(10)
            ->get();
        return view('events.show', compact('event', 'participation', 'topParticipants'));
    }

    /**
     * Register the user for the event.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register($id, Request $request)
    {
        $user = Auth::user();
        $event = Event::findOrFail($id);
        if (!$event->is_active || now()->gt($event->ends_at)) {
            return redirect()->back()->with('error', 'This event is no longer active.');
        }
        if ($event->access_level === 'vip' && !$user->isVip()) {
            return redirect()->back()->with('error', 'You need VIP access to participate in this event.');
        }
        $existingParticipation = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();
        if ($existingParticipation) {
            return redirect()->back()->with('error', 'You have already registered for this event.');
        }
        $participation = EventParticipant::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'registered',
        ]);
        $userStats = UserStat::firstOrCreate(['user_id' => $user->id]);
        $userStats->increment('events_participated');
        return redirect()->back()->with('success', 'You have successfully registered for this event.');
    }

    /**
     * Submit entry for the event.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit($id, Request $request)
    {
        $user = Auth::user();
        $event = Event::findOrFail($id);
        $participation = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->where('status', 'registered')
            ->firstOrFail();
        $submissionData = [];
        switch ($event->type) {
            case 'screenshot':
                $validated = $request->validate([
                    'screenshot' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'description' => 'nullable|string|max:500',
                ]);
                $imagePath = $request->file('screenshot')->store('event_submissions', 'public');
                $submissionData = [
                    'screenshot' => $imagePath,
                    'description' => $validated['description'],
                ];
                break;
            case 'custom':
                $submissionData = $request->except(['_token', '_method']);
                break;
            default:
                return redirect()->back()->with('error', 'Invalid event type for submission.');
        }
        $participation->update([
            'submission_data' => $submissionData,
            'status' => 'submitted',
        ]);
        return redirect()->back()->with('success', 'Your submission has been received successfully.');
    }

    /**
     * Vote for a participant.
     *
     * @param  int  $participantId
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function vote($participantId, Request $request)
    {
        $user = Auth::user();
        $participant = EventParticipant::with('event')
            ->findOrFail($participantId);
        if ($participant->event->ends_at < now()) {
            return redirect()->back()->with('error', 'Voting for this event has ended.');
        }
        $existingVote = EventVote::where('event_id', $participant->event_id)
            ->where('voter_id', $user->id)
            ->first();
        if ($existingVote) {
            return redirect()->back()->with('error', 'You have already voted in this event.');
        }
        EventVote::create([
            'event_id' => $participant->event_id,
            'participant_id' => $participant->id,
            'voter_id' => $user->id,
        ]);
        $participant->increment('votes');
        return redirect()->back()->with('success', 'Your vote has been recorded successfully.');
    }
    
    /**
     * Finalize event and assign rewards to winners.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function finalizeEvent($id)
    {
        $event = Event::findOrFail($id);
        
        if ($event->ends_at > now()) {
            return redirect()->back()->with('error', 'Event belum berakhir.');
        }
        
        // Get top 3 winners
        $winners = EventParticipant::where('event_id', $event->id)
            ->orderBy('votes', 'desc')
            ->take(3)
            ->get();
        
        foreach ($winners as $index => $winner) {
            $rank = $index + 1;
            
            // Update participant status
            $winner->update(['status' => 'winner']);
            
            // Assign XP reward
            if ($event->xp_reward) {
                $userStats = UserStat::firstOrCreate(['user_id' => $winner->user_id]);
                $xpAmount = $event->xp_reward;
                
                // Bonus XP for top 3
                if ($rank === 1) {
                    $xpAmount *= 1.5; // 150% for 1st place
                } elseif ($rank === 2) {
                    $xpAmount *= 1.25; // 125% for 2nd place
                } elseif ($rank === 3) {
                    $xpAmount *= 1.1; // 110% for 3rd place
                }
                
                $userStats->increment('xp', $xpAmount);
            }

            // Assign Coin reward
            if ($event->coin_reward) {
                $userStats = UserStat::firstOrCreate(['user_id' => $winner->user_id]);
                $coinAmount = $event->coin_reward;
                
                // Bonus Coins for top 3
                if ($rank === 1) {
                    $coinAmount *= 1.5;
                } elseif ($rank === 2) {
                    $coinAmount *= 1.25;
                } elseif ($rank === 3) {
                    $coinAmount *= 1.1;
                }
                
                $userStats->increment('coins', $coinAmount);
            }
            
            // Assign badge
            if ($event->badge_reward) {
                $badgeName = $event->badge_reward . " - Rank {$rank}";
                $badge = \App\Models\Badge::firstOrCreate([
                    'name' => $badgeName,
                ], [
                    'description' => "Juara {$rank} event: {$event->title}",
                    'icon' => $rank === 1 ? 'fas fa-trophy' : ($rank === 2 ? 'fas fa-medal' : 'fas fa-award'),
                    'color' => $rank === 1 ? '#FFD700' : ($rank === 2 ? '#C0C0C0' : '#CD7F32'),
                ]);
                
                // Assign badge to user
                \App\Models\UserBadge::firstOrCreate([
                    'user_id' => $winner->user_id,
                    'badge_id' => $badge->id,
                ], [
                    'awarded_at' => now(),
                ]);
            }
            
            // Assign VIP days
            if ($event->vip_days_reward) {
                $user = $winner->user;
                $vipDays = $event->vip_days_reward;
                
                if ($rank === 1) {
                    $vipDays *= 1.5;
                } elseif ($rank === 2) {
                    $vipDays *= 1.25;
                }
                
                $user->vip_until = now()->addDays($vipDays);
                $user->save();
            }
        }
        
        // Mark event as finalized
        $event->update(['is_active' => false]);
        
        return redirect()->back()->with('success', 'Event berhasil difinalisasi dan hadiah telah diberikan kepada pemenang!');
    }

    /**
     * Claim reward for auto-reward events.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function claimReward($id)
    {
        $user = Auth::user();
        $event = Event::findOrFail($id);
        
        if (!$event->is_active || now()->gt($event->ends_at)) {
            return back()->with('error', 'Event ended.');
        }

        if (!$event->auto_reward) {
            return back()->with('error', 'This event does not have auto-claimable rewards.');
        }

        $participation = EventParticipant::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$participation) {
            return back()->with('error', 'You must participate first.');
        }

        if ($participation->status === 'claimed') {
            return back()->with('error', 'Reward already claimed.');
        }

        // Give rewards
        $userStats = UserStat::firstOrCreate(['user_id' => $user->id]);
        if ($event->xp_reward) $userStats->increment('xp', $event->xp_reward);
        if ($event->coin_reward) $userStats->increment('coins', $event->coin_reward);
        
        $participation->update(['status' => 'claimed']);

        return back()->with('success', 'Reward claimed successfully!');
    }
}
