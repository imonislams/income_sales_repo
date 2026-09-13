<x-app-layout>
    <x-slot name="header">
        Financial Dashboard
    </x-slot>

    <div class="space-y-6">
        <!-- Quick Action Buttons & Greeting -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 rounded-2xl shadow-xl text-white">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                <p class="text-slate-300 text-sm mt-1">Here is a quick breakdown of your current financial status.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('income.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm shadow-lg shadow-emerald-500/20 transition-all hover:scale-[1.02]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Income
                </a>
                <a href="{{ route('expense.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-rose-500 hover:bg-rose-600 text-white font-semibold text-sm shadow-lg shadow-rose-500/20 transition-all hover:scale-[1.02]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    Add Expense
                </a>
                <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-sm border border-slate-700 transition-all">
                    View Transactions
                </a>
            </div>
        </div>

        <!-- 8 Metric Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- 1. Total Balance -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Balance</p>
                        <h3 class="text-2xl font-bold {{ $totalBalance >= 0 ? 'text-slate-900' : 'text-rose-600' }} mt-1">
                            ৳ {{ number_format($totalBalance, 2) }}
                        </h3>
                    </div>
                    <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 text-xs text-slate-500">Overall net balance</div>
            </div>

            <!-- 2. Total Income -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Income</p>
                        <h3 class="text-2xl font-bold text-emerald-600 mt-1">
                            ৳ {{ number_format($totalIncome, 2) }}
                        </h3>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                    </div>
                </div>
                <div class="mt-4 text-xs text-slate-500">Lifetime earnings</div>
            </div>

            <!-- 3. Total Expense -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Expense</p>
                        <h3 class="text-2xl font-bold text-rose-600 mt-1">
                            ৳ {{ number_format($totalExpense, 2) }}
                        </h3>
                    </div>
                    <div class="p-3 rounded-xl bg-rose-50 text-rose-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                    </div>
                </div>
                <div class="mt-4 text-xs text-slate-500">Lifetime spending</div>
            </div>

            <!-- 4. Monthly Balance -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Monthly Balance</p>
                        <h3 class="text-2xl font-bold {{ $monthlyBalance >= 0 ? 'text-indigo-600' : 'text-rose-600' }} mt-1">
                            ৳ {{ number_format($monthlyBalance, 2) }}
                        </h3>
                    </div>
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 text-xs text-slate-500">{{ now()->format('F Y') }} net</div>
            </div>

            <!-- 5. Today Income -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Today's Income</p>
                <p class="text-xl font-bold text-emerald-600 mt-1">৳ {{ number_format($todayIncome, 2) }}</p>
            </div>

            <!-- 6. Today Expense -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Today's Expense</p>
                <p class="text-xl font-bold text-rose-600 mt-1">৳ {{ number_format($todayExpense, 2) }}</p>
            </div>

            <!-- 7. Monthly Income -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Monthly Income ({{ now()->format('M') }})</p>
                <p class="text-xl font-bold text-emerald-600 mt-1">৳ {{ number_format($monthlyIncome, 2) }}</p>
            </div>

            <!-- 8. Monthly Expense -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Monthly Expense ({{ now()->format('M') }})</p>
                <p class="text-xl font-bold text-rose-600 mt-1">৳ {{ number_format($monthlyExpense, 2) }}</p>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Income vs Expense Chart (Current Year) -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-900 text-base">Income vs Expense ({{ now()->year }})</h3>
                    <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">Monthly Breakdown</span>
                </div>
                <div class="h-72">
                    <canvas id="incomeExpenseChart"></canvas>
                </div>
            </div>

            <!-- Expense Category Distribution Chart -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-900 text-base">Expense Categories</h3>
                    <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">Distribution</span>
                </div>
                <div class="h-72 flex items-center justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Recent Transactions</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Showing latest 10 transactions</p>
                </div>
                <a href="{{ route('transactions.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">View All →</a>
            </div>

            @if($recentTransactions->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h4 class="text-base font-bold text-slate-800">No transactions found</h4>
                    <p class="text-sm text-slate-500 max-w-sm mx-auto mt-1">Add your first income or expense to start tracking your finances effectively.</p>
                    <div class="mt-6 flex justify-center gap-3">
                        <a href="{{ route('income.create') }}" class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-semibold text-sm hover:bg-emerald-700">Add Income</a>
                        <a href="{{ route('expense.create') }}" class="px-4 py-2 rounded-xl bg-rose-600 text-white font-semibold text-sm hover:bg-rose-700">Add Expense</a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                                <th class="py-3.5 px-6">Date</th>
                                <th class="py-3.5 px-6">Description</th>
                                <th class="py-3.5 px-6">Category</th>
                                <th class="py-3.5 px-6">Type</th>
                                <th class="py-3.5 px-6">Payment Method</th>
                                <th class="py-3.5 px-6 text-right">Amount</th>
                                <th class="py-3.5 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                            @foreach($recentTransactions as $transaction)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-4 px-6 text-slate-600 whitespace-nowrap">
                                        {{ $transaction->date ? $transaction->date->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-slate-900">{{ $transaction->description ?: 'No description' }}</div>
                                        @if($transaction->note)
                                            <div class="text-xs text-slate-400 truncate max-w-xs">{{ $transaction->note }}</div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ $transaction->category }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @if($transaction->type === 'income')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Income
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                Expense
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap text-slate-600">
                                        {{ $transaction->payment_method ?: '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold whitespace-nowrap {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}৳ {{ number_format($transaction->amount, 2) }}
                                    </td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <a href="{{ route('transactions.show', $transaction->id) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors inline-block">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Income vs Expense Bar Chart
            const ctx1 = document.getElementById('incomeExpenseChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: @json($monthlyChart['months']),
                    datasets: [
                        {
                            label: 'Income (৳)',
                            data: @json($monthlyChart['income']),
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                        },
                        {
                            label: 'Expense (৳)',
                            data: @json($monthlyChart['expense']),
                            backgroundColor: '#f43f5e',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Expense Category Doughnut Chart
            const categoryData = @json($expenseCategories);
            const ctx2 = document.getElementById('categoryChart').getContext('2d');

            if (categoryData.length > 0) {
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: categoryData.map(item => item.category),
                        datasets: [{
                            data: categoryData.map(item => parseFloat(item.total)),
                            backgroundColor: [
                                '#f43f5e', '#8b5cf6', '#ec4899', '#f59e0b', '#3b82f6',
                                '#10b981', '#6366f1', '#14b8a6', '#64748b', '#06b6d4'
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 12 } }
                        }
                    }
                });
            } else {
                ctx2.font = '14px Plus Jakarta Sans';
                ctx2.fillStyle = '#94a3b8';
                ctx2.textAlign = 'center';
                ctx2.fillText('No expense data available', ctx2.canvas.width / 2, ctx2.canvas.height / 2);
            }
        });
    </script>
</x-app-layout>
