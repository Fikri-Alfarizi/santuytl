<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->orderByDesc('created_at')->get();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'open',
        ]);

        // Kirim ke Discord bot
        $botApiUrl = env('DISCORD_BOT_API_URL', 'http://localhost:3000');
        try {
            $response = Http::post("$botApiUrl/tickets", [
                'ticket_id' => $ticket->id,
                'user' => Auth::user()->name,
                'subject' => $ticket->subject,
                'message' => $ticket->message,
            ]);
            if ($response->successful() && isset($response['discord_ticket_id'])) {
                $ticket->discord_ticket_id = $response['discord_ticket_id'];
                $ticket->save();
            }
        } catch (\Exception $e) {}

        return redirect()->route('tickets.index')->with('success', 'Ticket berhasil dibuat!');
    }

    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }
}
