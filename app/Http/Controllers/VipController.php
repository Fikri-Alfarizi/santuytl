<?php

namespace App\Http\Controllers;

use App\Models\VipPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VipController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $hasActiveVip = $user->vip_expires_at && $user->vip_expires_at > now();
        $vipPurchases = VipPurchase::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('vip.index', compact('hasActiveVip', 'vipPurchases'));
    }

    public function purchase(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'days' => 'required|integer|in:7,30,90,365',
            'payment_method' => 'required|in:qris,dana,pulsa,bank_transfer,paypal',
        ]);
        $prices = [
            7 => 15000,
            30 => 50000,
            90 => 120000,
            365 => 400000,
        ];
        $amount = $prices[$validated['days']];
        $purchase = VipPurchase::create([
            'user_id' => $user->id,
            'days' => $validated['days'],
            'amount' => $amount,
            'currency' => 'IDR',
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
        ]);
        return redirect()->route('vip.payment', $purchase->id);
    }

    public function payment($id)
    {
        $user = Auth::user();
        $purchase = VipPurchase::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();
        return view('vip.payment', compact('purchase'));
    }

    public function confirmPayment($id, Request $request)
    {
        $user = Auth::user();
        $purchase = VipPurchase::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();
        $purchase->update([
            'status' => 'paid',
            'paid_at' => now(),
            'expires_at' => now()->addDays($purchase->days),
        ]);
        $currentExpiresAt = $user->vip_expires_at;
        $newExpiresAt = $currentExpiresAt > now()
            ? $currentExpiresAt->addDays($purchase->days)
            : now()->addDays($purchase->days);
        $user->update([
            'vip_expires_at' => $newExpiresAt,
        ]);
        return redirect()->route('vip.index')->with('success', 'VIP purchase successful! Your VIP status has been activated.');
    }
}
