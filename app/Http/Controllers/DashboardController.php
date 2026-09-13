```php
<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // ==============================
        // TOTAL INCOME
        // ==============================

        $totalIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->sum('amount');


        // ==============================
        // TOTAL EXPENSE
        // ==============================

        $totalExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->sum('amount');


        // ==============================
        // TOTAL BALANCE
        // ==============================

        $balance = $totalIncome - $totalExpense;


        // ==============================
        // TODAY INCOME
        // ==============================

        $todayIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereDate('date', today())
            ->sum('amount');


        // ==============================
        // TODAY EXPENSE
        // ==============================

        $todayExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereDate('date', today())
            ->sum('amount');


        // ==============================
        // MONTHLY INCOME
        // ==============================

        $monthlyIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');


        // ==============================
        // MONTHLY EXPENSE
        // ==============================

        $monthlyExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');


        // ==============================
        // MONTHLY BALANCE
        // ==============================

        $monthlyBalance = $monthlyIncome - $monthlyExpense;


        // ==============================
        // RECENT TRANSACTIONS
        // ==============================

        $recentTransactions = Transaction::where('user_id', $userId)
            ->latest('date')
            ->latest('id')
            ->take(10)
            ->get();


        // ==============================
        // MONTHLY CHART
        // ==============================

        $monthlyChart = [];

        for ($month = 1; $month <= 12; $month++) {

            $income = Transaction::where('user_id', $userId)
                ->where('type', 'income')
                ->whereMonth('date', $month)
                ->whereYear('date', now()->year)
                ->sum('amount');

            $expense = Transaction::where('user_id', $userId)
                ->where('type', 'expense')
                ->whereMonth('date', $month)
                ->whereYear('date', now()->year)
                ->sum('amount');

            $monthlyChart[] = [
                'income' => (float) $income,
                'expense' => (float) $expense,
            ];
        }


        // ==============================
        // EXPENSE CATEGORY CHART
        // ==============================

        $categoryData = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();


        return view('dashboard', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'todayIncome',
            'todayExpense',
            'monthlyIncome',
            'monthlyExpense',
            'monthlyBalance',
            'recentTransactions',
            'monthlyChart',
            'categoryData'
        ));
    }
}
