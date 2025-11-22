<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BankAccount;
use App\Models\CoinTransaction;

class BankController extends Controller
{
    /**
     * Show bank dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $bankAccount = BankAccount::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'interest_rate' => 0.0100]
        );

        $transactions = CoinTransaction::where('user_id', $user->id)
            ->whereIn('type', ['bank_deposit', 'bank_withdraw', 'interest'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bank.index', compact('user', 'bankAccount', 'transactions'));
    }

    /**
     * Deposit coins to bank
     */
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:100',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        if ($user->stats->coins < $amount) {
            return back()->with('error', 'Koin Anda tidak mencukupi.');
        }

        DB::transaction(function () use ($user, $amount) {
            $bankAccount = BankAccount::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0, 'interest_rate' => 0.0100]
            );

            // Deduct from wallet
            $user->stats->coins -= $amount;
            $user->stats->save();

            // Add to bank
            $bankAccount->balance += $amount;
            $bankAccount->save();

            // Log transaction
            CoinTransaction::create([
                'user_id' => $user->id,
                'amount' => -$amount,
                'type' => 'bank_deposit',
                'description' => "Deposit ke Bank",
            ]);
        });

        return back()->with('success', "Berhasil deposit {$amount} koin.");
    }

    /**
     * Withdraw coins from bank
     */
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:100',
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        $bankAccount = BankAccount::where('user_id', $user->id)->firstOrFail();

        if ($bankAccount->balance < $amount) {
            return back()->with('error', 'Saldo Bank tidak mencukupi.');
        }

        DB::transaction(function () use ($user, $bankAccount, $amount) {
            // Deduct from bank
            $bankAccount->balance -= $amount;
            $bankAccount->save();

            // Add to wallet
            $user->stats->coins += $amount;
            $user->stats->save();

            // Log transaction
            CoinTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'bank_withdraw',
                'description' => "Penarikan dari Bank",
            ]);
        });

        return back()->with('success', "Berhasil menarik {$amount} koin.");
    }
}
