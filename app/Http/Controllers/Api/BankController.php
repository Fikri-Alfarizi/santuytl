<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankAccount;

class BankController extends Controller
{
    public function index()
    {
        $accounts = BankAccount::with('user')->paginate(20);
        return view('bank.index', compact('accounts'));
    }
    // ...deposit, withdraw
}
