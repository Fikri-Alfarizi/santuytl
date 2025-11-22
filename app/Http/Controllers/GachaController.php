<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\UserInventory;
use App\Models\CoinTransaction;

class GachaController extends Controller
{
    /**
     * Show gacha page
     */
    public function index()
    {
        $user = Auth::user();
        $items = Item::where('gacha_chance', '>', 0)
            ->where('is_active', true)
            ->orderBy('gacha_chance', 'asc') // Show rarest first (lowest chance)
            ->get();
        
        return view('gacha.index', compact('user', 'items'));
    }

    /**
     * Perform gacha spin
     */
    public function spin(Request $request)
    {
        $user = Auth::user();
        $cost = 500; // Cost per spin

        if ($user->stats->coins < $cost) {
            return back()->with('error', 'Koin tidak mencukupi. Butuh 500 koin.');
        }

        // Get all gacha items
        $items = Item::where('gacha_chance', '>', 0)
            ->where('is_active', true)
            ->get();

        if ($items->isEmpty()) {
            return back()->with('error', 'Gacha sedang tidak tersedia.');
        }

        // Gacha Logic
        $totalChance = $items->sum('gacha_chance');
        $random = mt_rand(1, $totalChance * 100) / 100;
        $current = 0;
        $wonItem = null;

        foreach ($items as $item) {
            $current += $item->gacha_chance;
            if ($random <= $current) {
                $wonItem = $item;
                break;
            }
        }

        // Fallback if float precision issues (should rarely happen)
        if (!$wonItem) {
            $wonItem = $items->last();
        }

        DB::transaction(function () use ($user, $cost, $wonItem) {
            // Deduct coins
            $user->stats->coins -= $cost;
            $user->stats->save();

            // Add item to inventory
            $inventory = UserInventory::firstOrCreate(
                ['user_id' => $user->id, 'item_id' => $wonItem->id],
                ['quantity' => 0]
            );
            $inventory->quantity += 1;
            $inventory->save();

            // Log transaction
            CoinTransaction::create([
                'user_id' => $user->id,
                'amount' => -$cost,
                'type' => 'spend',
                'description' => "Gacha Spin: Dapat {$wonItem->name}",
            ]);
        });

        return back()->with('success', "Selamat! Anda mendapatkan **{$wonItem->name}** ({$wonItem->rarity})!");
    }
}
