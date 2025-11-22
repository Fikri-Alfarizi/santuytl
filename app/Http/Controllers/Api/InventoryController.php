<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserInventory;

class InventoryController extends Controller
{
    public function index()
    {
        $items = UserInventory::with('item')->where('user_id', auth()->id())->get();
        return view('minigames.index', compact('items'));
    }
}
