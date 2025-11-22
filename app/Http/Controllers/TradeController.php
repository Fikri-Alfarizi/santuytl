<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Trade;
use App\Models\User;
use App\Models\UserInventory;
use App\Models\CoinTransaction;

class TradeController extends Controller
{
    /**
     * Show trades list
     */
    public function index()
    {
        $user = Auth::user();
        $trades = Trade::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('trades.index', compact('trades'));
    }

    /**
     * Show create trade form
     */
    public function create()
    {
        $user = Auth::user();
        $users = User::where('id', '!=', $user->id)->get(); // Should be optimized for large user base
        $inventory = UserInventory::where('user_id', $user->id)
            ->whereHas('item', function($q) {
                $q->where('is_tradeable', true);
            })
            ->with('item')
            ->get();

        return view('trades.create', compact('users', 'inventory'));
    }

    /**
     * Store trade request
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'coins' => 'nullable|integer|min:0',
            'items' => 'nullable|array',
            'items.*.inventory_id' => 'required|exists:user_inventory,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        if ($request->receiver_id == $user->id) {
            return back()->with('error', 'Tidak bisa trade dengan diri sendiri.');
        }

        // Validate coins
        if ($request->coins && $user->stats->coins < $request->coins) {
            return back()->with('error', 'Koin tidak mencukupi.');
        }

        // Validate items
        $tradeItems = [];
        if ($request->items) {
            foreach ($request->items as $itemData) {
                $inventory = UserInventory::where('user_id', $user->id)
                    ->where('id', $itemData['inventory_id'])
                    ->firstOrFail();
                
                if ($inventory->quantity < $itemData['quantity']) {
                    return back()->with('error', "Jumlah item {$inventory->item->name} tidak mencukupi.");
                }

                $tradeItems[] = [
                    'item_id' => $inventory->item_id,
                    'quantity' => $itemData['quantity'],
                    'inventory_id' => $inventory->id // Keep track for deduction later
                ];
            }
        }

        Trade::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'sender_items' => $tradeItems,
            'sender_coins' => $request->coins ?? 0,
            'status' => 'pending',
        ]);

        return redirect()->route('trades.index')->with('success', 'Permintaan trade terkirim.');
    }

    /**
     * Accept trade
     */
    public function accept(Trade $trade)
    {
        $user = Auth::user();

        if ($trade->receiver_id !== $user->id) {
            abort(403);
        }

        if ($trade->status !== 'pending') {
            return back()->with('error', 'Trade sudah tidak aktif.');
        }

        DB::transaction(function () use ($trade) {
            // Process Sender Items -> Receiver
            if ($trade->sender_items) {
                foreach ($trade->sender_items as $itemData) {
                    // Deduct from sender
                    $senderInv = UserInventory::where('user_id', $trade->sender_id)
                        ->where('item_id', $itemData['item_id'])
                        ->first();
                    
                    if (!$senderInv || $senderInv->quantity < $itemData['quantity']) {
                        throw new \Exception("Sender items changed.");
                    }

                    if ($senderInv->quantity == $itemData['quantity']) {
                        $senderInv->delete();
                    } else {
                        $senderInv->quantity -= $itemData['quantity'];
                        $senderInv->save();
                    }

                    // Add to receiver
                    $receiverInv = UserInventory::firstOrCreate(
                        ['user_id' => $trade->receiver_id, 'item_id' => $itemData['item_id']],
                        ['quantity' => 0]
                    );
                    $receiverInv->quantity += $itemData['quantity'];
                    $receiverInv->save();
                }
            }

            // Process Coins
            if ($trade->sender_coins > 0) {
                $trade->sender->stats->coins -= $trade->sender_coins;
                $trade->sender->stats->save();

                $trade->receiver->stats->coins += $trade->sender_coins;
                $trade->receiver->stats->save();

                CoinTransaction::create([
                    'user_id' => $trade->sender_id,
                    'amount' => -$trade->sender_coins,
                    'type' => 'trade',
                    'description' => "Trade ke {$trade->receiver->username}",
                ]);

                CoinTransaction::create([
                    'user_id' => $trade->receiver_id,
                    'amount' => $trade->sender_coins,
                    'type' => 'trade',
                    'description' => "Trade dari {$trade->sender->username}",
                ]);
            }

            $trade->status = 'accepted';
            $trade->completed_at = now();
            $trade->save();
        });

        return back()->with('success', 'Trade berhasil diterima.');
    }

    /**
     * Reject trade
     */
    public function reject(Trade $trade)
    {
        $user = Auth::user();

        if ($trade->receiver_id !== $user->id) {
            abort(403);
        }

        if ($trade->status !== 'pending') {
            return back()->with('error', 'Trade sudah tidak aktif.');
        }

        $trade->status = 'rejected';
        $trade->save();

        return back()->with('success', 'Trade ditolak.');
    }

    /**
     * Cancel trade
     */
    public function cancel(Trade $trade)
    {
        $user = Auth::user();

        if ($trade->sender_id !== $user->id) {
            abort(403);
        }

        if ($trade->status !== 'pending') {
            return back()->with('error', 'Trade sudah tidak aktif.');
        }

        $trade->status = 'cancelled';
        $trade->save();

        return back()->with('success', 'Trade dibatalkan.');
    }
}
