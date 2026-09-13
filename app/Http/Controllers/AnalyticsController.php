<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $filter = $request->input('filter', 'this_month');

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if ($filter === 'this_month') {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        } elseif ($filter === 'last_month') {
            $startDate = now()->subMonth()->startOfMonth()->toDateString();
            $endDate = now()->subMonth()->endOfMonth()->toDateString();
        } elseif ($filter === 'last_3_months') {
            $startDate = now()->subMonths(2)->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        } elseif ($filter === 'last_6_months') {
            $startDate = now()->subMonths(5)->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        } elseif ($filter === 'this_year') {
            $startDate = now()->startOfYear()->toDateString();
            $endDate = now()->endOfYear()->toDateString();
        } elseif ($filter === 'custom' && $fromDate && $toDate) {
            $startDate = $fromDate;
            $endDate = $toDate;
        } else {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        }

        $baseQuery = Transaction::where('user_id', $userId)
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate);

        // Summary metrics
        $totalIncome = (clone $baseQuery)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $baseQuery)->where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        $thisMonthIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')
            ->where('date', 'like', now()->format('Y-m') . '%')
            ->sum('amount');

        $thisMonthExpense = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->where('date', 'like', now()->format('Y-m') . '%')
            ->sum('amount');

        // Income by category table & pie chart
        $incomeByCat = (clone $baseQuery)
            ->where('type', 'income')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        $incomeCategoryTable = $incomeByCat->map(function ($item) use ($totalIncome) {
            $percentage = $totalIncome > 0 ? round(($item->total / $totalIncome) * 100, 1) : 0;
            return [
                'category' => $item->category,
                'total' => (float)$item->total,
                'percentage' => $percentage,
            ];
        });

        // Expense by category table & pie chart
        $expenseByCat = (clone $baseQuery)
            ->where('type', 'expense')
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        $expenseCategoryTable = $expenseByCat->map(function ($item) use ($totalExpense) {
            $percentage = $totalExpense > 0 ? round(($item->total / $totalExpense) * 100, 1) : 0;
            return [
                'category' => $item->category,
                'total' => (float)$item->total,
                'percentage' => $percentage,
            ];
        });

        // Monthly trends
        $driver = DB::connection()->getDriverName();
        $monthSql = $driver === 'sqlite' ? 'strftime("%Y-%m", date)' : 'DATE_FORMAT(date, "%Y-%m")';

        $monthlyData = (clone $baseQuery)
            ->selectRaw("type, {$monthSql} as month_key, SUM(amount) as total")
            ->groupBy('type', DB::raw($monthSql))
            ->orderBy(DB::raw($monthSql))
            ->get();

        $monthlyChart = [
            'labels' => [],
            'income' => [],
            'expense' => [],
            'net_trend' => [],
        ];

        $groupedByMonth = [];
        foreach ($monthlyData as $row) {
            $m = $row->month_key;
            if (!isset($groupedByMonth[$m])) {
                $groupedByMonth[$m] = ['income' => 0, 'expense' => 0];
            }
            if ($row->type === 'income') {
                $groupedByMonth[$m]['income'] = (float)$row->total;
            } else {
                $groupedByMonth[$m]['expense'] = (float)$row->total;
            }
        }

        ksort($groupedByMonth);

        foreach ($groupedByMonth as $mKey => $vals) {
            $monthlyChart['labels'][] = date('M Y', strtotime($mKey . '-01'));
            $monthlyChart['income'][] = $vals['income'];
            $monthlyChart['expense'][] = $vals['expense'];
            $monthlyChart['net_trend'][] = $vals['income'] - $vals['expense'];
        }

        // Insights calculations
        $highestIncomeCategory = $incomeCategoryTable->first()['category'] ?? 'N/A';
        $highestExpenseCategory = $expenseCategoryTable->first()['category'] ?? 'N/A';

        $distinctMonthsCount = max(1, count($groupedByMonth));
        $avgMonthlyIncome = $totalIncome / $distinctMonthsCount;
        $avgMonthlyExpense = $totalExpense / $distinctMonthsCount;

        $insights = [
            "Your highest income source is {$highestIncomeCategory}.",
            "Your highest expense category is {$highestExpenseCategory}.",
            "Your total income for the period is ৳" . number_format($totalIncome, 2) . ".",
            "Your total expense for the period is ৳" . number_format($totalExpense, 2) . ".",
            "Your current net balance for the period is ৳" . number_format($netBalance, 2) . ".",
            "Your average monthly income is ৳" . number_format($avgMonthlyIncome, 2) . ".",
            "Your average monthly expense is ৳" . number_format($avgMonthlyExpense, 2) . ".",
        ];

        return view('analytics.index', compact(
            'filter',
            'startDate',
            'endDate',
            'totalIncome',
            'totalExpense',
            'netBalance',
            'thisMonthIncome',
            'thisMonthExpense',
            'incomeCategoryTable',
            'expenseCategoryTable',
            'monthlyChart',
            'insights'
        ));
    }
}
