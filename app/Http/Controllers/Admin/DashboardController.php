<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Bet;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'active_users' => User::where('role', 'user')->where('status', 'active')->count(),
            'total_deposits' => Deposit::where('status', 'completed')->sum('amount'),
            'total_withdrawals' => Withdrawal::where('status', 'completed')->sum('amount'),
            'pending_withdrawals' => Withdrawal::where('status', 'pending')->count(),
            'total_bets' => Bet::count(),
            'revenue' => Transaction::where('type', 'bet')->sum('amount') - Transaction::where('type', 'win')->sum('amount'),
        ];

        $recent_users = User::where('role', 'user')->with('wallet')->latest()->take(5)->get();
        $recent_deposits = Deposit::with('user')->latest()->take(5)->get();
        $pending_withdrawals = Withdrawal::with('user')->where('status', 'pending')->latest()->take(5)->get();

        $daily_stats = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(CASE WHEN type = "deposit" THEN amount ELSE 0 END) as deposits'),
            DB::raw('SUM(CASE WHEN type = "withdrawal" THEN amount ELSE 0 END) as withdrawals'),
            DB::raw('SUM(CASE WHEN type = "bet" THEN amount ELSE 0 END) as bets')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_deposits', 'pending_withdrawals', 'daily_stats'));
    }
}