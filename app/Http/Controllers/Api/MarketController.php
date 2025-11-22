<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MarketListing;

class MarketController extends Controller
{
    public function index()
    {
        $listings = MarketListing::with('item', 'seller')->where('is_active', true)->paginate(20);
        return view('market.index', compact('listings'));
    }
    // ...buy, sell
}
