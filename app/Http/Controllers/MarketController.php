<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MarketListing;
use App\Models\Item;
use App\Models\UserInventory;
use App\Models\CoinTransaction;

class MarketController extends Controller
{
    /**
     * Show market listings
     */
    public function index()
    {
        $listings = MarketListing::active()
            ->with(['seller', 'item'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('market.index', compact('listings'));
    }

    /**
     * Buy an item from market
     */
    public function buy(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:market_listings,id',
        ]);

        $user = Auth::user();
        $listing = MarketListing::with('item')->findOrFail($request->listing_id);

        if ($listing->status !== 'active') {
            return back()->with('error', 'Item ini sudah tidak tersedia.');
        }

        if ($listing->seller_id === $user->id) {
            return back()->with('error', 'Anda tidak bisa membeli item Anda sendiri.');
        }

        $totalPrice = $listing->price_coins;

        // Check balance
        if ($user->stats->coins < $totalPrice) {
            return back()->with('error', 'Koin Anda tidak mencukupi.');
        }

        DB::transaction(function () use ($user, $listing, $totalPrice) {
            // Deduct buyer coins
            $user->stats->coins -= $totalPrice;
            $user->stats->save();

            // Add seller coins
            $listing->seller->stats->coins += $totalPrice;
            $listing->seller->stats->save();

            // Transfer item
            UserInventory::create([
                'user_id' => $user->id,
                'item_id' => $listing->item_id,
                'quantity' => $listing->quantity,
            ]);

            // Update listing status
            $listing->status = 'sold';
            $listing->sold_at = now();
            $listing->save();

            // Log transactions
            CoinTransaction::create([
                'user_id' => $user->id,
                'amount' => -$totalPrice,
                'type' => 'market_buy',
                'description' => "Membeli {$listing->item->name} dari {$listing->seller->username}",
                'metadata' => ['listing_id' => $listing->id],
            ]);

            CoinTransaction::create([
                'user_id' => $listing->seller_id,
                'amount' => $totalPrice,
                'type' => 'market_sell',
                'description' => "Menjual {$listing->item->name} ke {$user->username}",
                'metadata' => ['listing_id' => $listing->id],
            ]);
        });

        return redirect()->route('market.index')->with('success', "Berhasil membeli {$listing->item->name}!");
    }

    /**
     * Show sell item form
     */
    public function sell()
    {
        $user = Auth::user();
        $inventory = UserInventory::where('user_id', $user->id)
            ->whereHas('item', function($q) {
                $q->where('is_tradeable', true);
            })
            ->with('item')
            ->get();

        return view('market.sell', compact('inventory'));
    }

    /**
     * Create market listing
     */
    public function store(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:user_inventory,id',
            'price' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $inventory = UserInventory::where('user_id', $user->id)
            ->where('id', $request->inventory_id)
            ->firstOrFail();

        if ($inventory->quantity < $request->quantity) {
            return back()->with('error', 'Jumlah item tidak mencukupi.');
        }

        DB::transaction(function () use ($user, $inventory, $request) {
            // Create listing
            MarketListing::create([
                'seller_id' => $user->id,
                'item_id' => $inventory->item_id,
                'price_coins' => $request->price,
                'quantity' => $request->quantity,
                'status' => 'active',
            ]);

            // Reduce inventory
            if ($inventory->quantity == $request->quantity) {
                $inventory->delete();
            } else {
                $inventory->quantity -= $request->quantity;
                $inventory->save();
            }
        });

        return redirect()->route('market.index')->with('success', 'Item berhasil didaftarkan di market.');
    }
}
