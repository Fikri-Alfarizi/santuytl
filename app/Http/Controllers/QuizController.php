<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserStat;
use App\Models\CoinTransaction;
use App\Models\GameHistory;

class QuizController extends Controller
{
    public function index()
    {
        // Generate simple math question
        $operators = ['+', '-', '*'];
        $operator = $operators[array_rand($operators)];
        $num1 = mt_rand(1, 20);
        $num2 = mt_rand(1, 20);
        
        if ($operator == '-') {
            // Ensure positive result for simplicity
            if ($num1 < $num2) {
                [$num1, $num2] = [$num2, $num1];
            }
        }

        $question = "$num1 $operator $num2";
        $answer = eval("return $question;");
        
        // Encrypt answer to prevent simple inspect element cheating (basic)
        $encryptedAnswer = encrypt($answer);

        return view('quiz.index', compact('question', 'encryptedAnswer'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'answer' => 'required|integer',
            'token' => 'required',
        ]);

        $user = Auth::user();
        
        try {
            $realAnswer = decrypt($request->token);
        } catch (\Exception $e) {
            return back()->with('error', 'Invalid session.');
        }

        if ($request->answer == $realAnswer) {
            // Correct!
            $rewardCoins = 50;
            $rewardXp = 20;

            $user->stats->coins += $rewardCoins;
            $user->stats->xp += $rewardXp;
            $user->stats->save();

            CoinTransaction::create([
                'user_id' => $user->id,
                'amount' => $rewardCoins,
                'type' => 'earn',
                'description' => 'Quiz Reward',
            ]);

            GameHistory::create([
                'user_id' => $user->id,
                'game_type' => 'quiz',
                'result' => ['correct' => true],
                'reward' => ['coins' => $rewardCoins, 'xp' => $rewardXp],
                'played_at' => now(),
            ]);

            return back()->with('success', "Benar! Kamu dapat $rewardCoins Koin dan $rewardXp XP.");
        } else {
            return back()->with('error', 'Salah! Coba lagi.');
        }
    }
}
