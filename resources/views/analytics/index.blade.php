<x-app-layout>
    <x-slot name="header">
        Analytics
    </x-slot>

    <div class="space-y-8">
        <!-- Date Filter Bar -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm print:hidden">
            <form method="GET" action="{{ route('analytics.index') }}" class="flex flex-col md:flex-row md:items-end gap-4" id="analytics-filter-form">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Timeframe Filter</label>
                    <select name="filter" onchange="document.getElementById('custom-date-container').style.display = this.value === 'custom' ? 'flex' : 'none';" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="this_month" {{ $filter === 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_month" {{ $filter === 'last_month' ? 'selected' : '' }}>Last Month</option>
                        <option value="last_3_months" {{ $filter === 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                        <option value="last_6_months" {{ $filter === 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                        <option value="this_year" {{ $filter === 'this_year' ? 'selected' : '' }}>This Year</option>
                        <option value="custom" {{ $filter === 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                    </select>
                </div>

                <div id="custom-date-container" class="flex gap-2" style="display: {{ $filter === 'custom' ? 'flex' : 'none' }};">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date', $startDate) }}" class="rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">To Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date', $endDate) }}" class="rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-md transition-colors">
                        Apply Filter
                    </button>
                    <button type="button" onclick="window.print()" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-xl shadow-md transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print Report
                    </button>
                </div>
            </form>
        </div>

        <!-- 5 Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Income</p>
                <h3 class="text-xl font-bold text-emerald-600 mt-1">৳ {{ number_format($totalIncome, 2) }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Expense</p>
                <h3 class="text-xl font-bold text-rose-600 mt-1">৳ {{ number_format($totalExpense, 2) }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500 uppercase">Net Balance</p>
                <h3 class="text-xl font-bold {{ $netBalance >= 0 ? 'text-indigo-600' : 'text-rose-600' }} mt-1">৳ {{ number_format($netBalance, 2) }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500 uppercase">This Month Income</p>
                <h3 class="text-xl font-bold text-emerald-600 mt-1">৳ {{ number_format($thisMonthIncome, 2) }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500 uppercase">This Month Expense</p>
                <h3 class="text-xl font-bold text-rose-600 mt-1">৳ {{ number_format($thisMonthExpense, 2) }}</h3>
            </div>
        </div>

        <!-- 6 Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1: Income vs Expense -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-base mb-4">Chart 1: Income vs Expense</h3>
                <div class="h-64"><canvas id="chartIncomeVsExpense"></canvas></div>
            </div>

            <!-- Chart 2: Income by Category -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-base mb-4">Chart 2: Income by Category</h3>
                <div class="h-64 flex items-center justify-center"><canvas id="chartIncomeCategory"></canvas></div>
            </div>

            <!-- Chart 3: Expense by Category -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-base mb-4">Chart 3: Expense by Category</h3>
                <div class="h-64 flex items-center justify-center"><canvas id="chartExpenseCategory"></canvas></div>
            </div>

            <!-- Chart 4: Monthly Income -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-base mb-4">Chart 4: Monthly Income Trend</h3>
                <div class="h-64"><canvas id="chartMonthlyIncome"></canvas></div>
            </div>

            <!-- Chart 5: Monthly Expense -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-base mb-4">Chart 5: Monthly Expense Trend</h3>
                <div class="h-64"><canvas id="chartMonthlyExpense"></canvas></div>
            </div>

            <!-- Chart 6: Net Balance Trend -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-base mb-4">Chart 6: Net Balance Trend</h3>
                <div class="h-64"><canvas id="chartNetBalanceTrend"></canvas></div>
            </div>
        </div>

        <!-- Category Analytics Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Income by Category Table -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-base mb-4">INCOME BY CATEGORY</h3>
                @if(count($incomeCategoryTable) === 0)
                    <p class="text-sm text-slate-400 text-center py-6">No income recorded for this period.</p>
                @else
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase">
                                <th class="py-2">Category</th>
                                <th class="py-2 text-right">Total Amount</th>
                                <th class="py-2 text-right">Percentage</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($incomeCategoryTable as $row)
                                <tr>
                                    <td class="py-3 font-semibold text-slate-800">{{ $row['category'] }}</td>
                                    <td class="py-3 text-right text-emerald-600 font-bold">৳ {{ number_format($row['total'], 2) }}</td>
                                    <td class="py-3 text-right text-slate-600">{{ $row['percentage'] }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Expense by Category Table -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-base mb-4">EXPENSE BY CATEGORY</h3>
                @if(count($expenseCategoryTable) === 0)
                    <p class="text-sm text-slate-400 text-center py-6">No expense recorded for this period.</p>
                @else
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase">
                                <th class="py-2">Category</th>
                                <th class="py-2 text-right">Total Amount</th>
                                <th class="py-2 text-right">Percentage</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($expenseCategoryTable as $row)
                                <tr>
                                    <td class="py-3 font-semibold text-slate-800">{{ $row['category'] }}</td>
                                    <td class="py-3 text-right text-rose-600 font-bold">৳ {{ number_format($row['total'], 2) }}</td>
                                    <td class="py-3 text-right text-slate-600">{{ $row['percentage'] }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <!-- Financial Insights -->
        <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 p-6 rounded-2xl text-white shadow-xl">
            <h3 class="text-lg font-bold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Financial Insights
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                @foreach($insights as $insight)
                    <div class="bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/10">
                        <p class="text-slate-200 font-medium">{{ $insight }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthlyLabels = @json($monthlyChart['labels']);
            const monthlyIncome = @json($monthlyChart['income']);
            const monthlyExpense = @json($monthlyChart['expense']);
            const netTrend = @json($monthlyChart['net_trend']);

            const incomeCatData = @json($incomeCategoryTable);
            const expenseCatData = @json($expenseCategoryTable);

            // Palette
            const colors = ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ec4899', '#06b6d4', '#64748b'];
            const roseColors = ['#f43f5e', '#ec4899', '#f59e0b', '#8b5cf6', '#3b82f6', '#06b6d4', '#64748b'];

            // 1. Income vs Expense
            new Chart(document.getElementById('chartIncomeVsExpense'), {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [
                        { label: 'Income (৳)', data: monthlyIncome, backgroundColor: '#10b981', borderRadius: 4 },
                        { label: 'Expense (৳)', data: monthlyExpense, backgroundColor: '#f43f5e', borderRadius: 4 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // 2. Income Category Pie
            new Chart(document.getElementById('chartIncomeCategory'), {
                type: 'doughnut',
                data: {
                    labels: incomeCatData.map(i => i.category),
                    datasets: [{ data: incomeCatData.map(i => i.total), backgroundColor: colors }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });

            // 3. Expense Category Pie
            new Chart(document.getElementById('chartExpenseCategory'), {
                type: 'doughnut',
                data: {
                    labels: expenseCatData.map(e => e.category),
                    datasets: [{ data: expenseCatData.map(e => e.total), backgroundColor: roseColors }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });

            // 4. Monthly Income
            new Chart(document.getElementById('chartMonthlyIncome'), {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{ label: 'Monthly Income (৳)', data: monthlyIncome, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.3 }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // 5. Monthly Expense
            new Chart(document.getElementById('chartMonthlyExpense'), {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{ label: 'Monthly Expense (৳)', data: monthlyExpense, borderColor: '#f43f5e', backgroundColor: 'rgba(244,63,94,0.1)', fill: true, tension: 0.3 }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // 6. Net Balance Trend
            new Chart(document.getElementById('chartNetBalanceTrend'), {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{ label: 'Net Balance Trend (৳)', data: netTrend, borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,0.1)', fill: true, tension: 0.3 }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });
    </script>
</x-app-layout>
