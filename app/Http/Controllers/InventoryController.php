<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserInventory;
use App\Models\Item;

class InventoryController extends Controller
{
    /**
     * Show user inventory
     */
    public function index()
    {
        $user = Auth::user();
        $inventory = UserInventory::where('user_id', $user->id)
            ->with('item')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('inventory.index', compact('user', 'inventory'));
    }

    /**
     * Equip an item (avatar frame, theme, etc)
     */
    public function equip(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:user_inventory,id',
        ]);

        $user = Auth::user();
        $inventoryItem = UserInventory::where('user_id', $user->id)
            ->where('id', $request->inventory_id)
            ->firstOrFail();

        $itemType = $inventoryItem->item->type;

        // Unequip other items of the same type
        UserInventory::where('user_id', $user->id)
            ->whereHas('item', function($q) use ($itemType) {
                $q->where('type', $itemType);
            })
            ->update(['is_equipped' => false]);

        // Equip the selected item
        $inventoryItem->is_equipped = true;
        $inventoryItem->save();

        return back()->with('success', "Item {$inventoryItem->item->name} berhasil digunakan.");
    }
}
