<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $today = now()->format('Y-m-d');
        $currentMonth = now()->format('Y-m');
        $currentYear = now()->year;

        // Base query scoped to auth user
        $baseQuery = Transaction::where('user_id', $userId);

        $totalIncome = (clone $baseQuery)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $baseQuery)->where('type', 'expense')->sum('amount');
        $totalBalance = $totalIncome - $totalExpense;

        $todayIncome = (clone $baseQuery)->where('type', 'income')->whereDate('date', $today)->sum('amount');
        $todayExpense = (clone $baseQuery)->where('type', 'expense')->whereDate('date', $today)->sum('amount');

        $monthlyIncome = (clone $baseQuery)->where('type', 'income')->where('date', 'like', "$currentMonth%")->sum('amount');
        $monthlyExpense = (clone $baseQuery)->where('type', 'expense')->where('date', 'like', "$currentMonth%")->sum('amount');
        $monthlyBalance = $monthlyIncome - $monthlyExpense;

        // Recent 10 transactions
        $recentTransactions = (clone $baseQuery)->latest('date')->latest('id')->take(10)->get();

        // Income vs Expense chart data for current year (12 months)
        $monthlyChart = [
            'months' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'income' => array_fill(0, 12, 0),
            'expense' => array_fill(0, 12, 0),
        ];

        $driver = DB::connection()->getDriverName();
        $monthSql = $driver === 'sqlite' ? 'strftime("%m", date)' : 'MONTH(date)';

        $yearTransactions = (clone $baseQuery)
            ->whereYear('date', $currentYear)
            ->selectRaw("type, {$monthSql} as month, SUM(amount) as total")
            ->groupBy('type', DB::raw($monthSql))
            ->get();

        foreach ($yearTransactions as $row) {
            $monthIndex = (int)$row->month - 1;
            if ($monthIndex >= 0 && $monthIndex < 12) {
                if ($row->type === 'income') {
                    $monthlyChart['income'][$monthIndex] = (float)$row->total;
                } else {
                    $monthlyChart['expense'][$monthIndex] = (float)$row->total;
                }
            }
        }

        // Expense category distribution
        $expenseCategories = (clone $baseQuery)
            ->where('type', 'expense')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        return view('dashboard', compact(
            'totalBalance',
            'totalIncome',
            'totalExpense',
            'monthlyBalance',
            'todayIncome',
            'todayExpense',
            'monthlyIncome',
            'monthlyExpense',
            'recentTransactions',
            'monthlyChart',
            'expenseCategories'
        ));
    }
}
