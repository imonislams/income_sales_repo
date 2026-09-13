<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Transaction::where('user_id', $userId);

        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $dateTo);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $totalIncome = (clone $query)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $query)->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;
        $transactionCount = (clone $query)->count();

        $expenseByCategory = (clone $query)
            ->where('type', 'expense')
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->get();

        $incomeByCategory = (clone $query)
            ->where('type', 'income')
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->get();

        $categories = Category::where(function ($q) use ($userId) {
            $q->whereNull('user_id')->orWhere('user_id', $userId);
        })->pluck('name')->unique();

        return view('reports.index', compact(
            'totalIncome',
            'totalExpense',
            'balance',
            'transactionCount',
            'expenseByCategory',
            'incomeByCategory',
            'dateFrom',
            'dateTo',
            'categories'
        ));
    }
}
