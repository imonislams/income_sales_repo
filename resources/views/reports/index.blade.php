<x-app-layout>
    <x-slot name="header">
        Financial Reports
    </x-slot>

    <div class="space-y-6">
        <!-- Header Actions & Print Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Financial Performance & Analytics</h2>
                <p class="text-sm text-slate-500">Detailed summary of financial activities within selected timeframes.</p>
            </div>
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm shadow-md transition-all self-start sm:self-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Report
            </button>
        </div>

        <!-- Filter Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm print:hidden">
            <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from', $dateFrom) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to', $dateTo) }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Category</label>
                    <select name="category" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Type</label>
                    <select name="type" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Types</option>
                        <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl transition-colors">Generate Report</button>
                    <a href="{{ route('reports.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition-colors">Reset</a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Income</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">৳ {{ number_format($totalIncome, 2) }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Expense</p>
                <h3 class="text-2xl font-bold text-rose-600 mt-1">৳ {{ number_format($totalExpense, 2) }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500 uppercase">Net Balance</p>
                <h3 class="text-2xl font-bold {{ $balance >= 0 ? 'text-indigo-600' : 'text-rose-600' }} mt-1">৳ {{ number_format($balance, 2) }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <p class="text-xs font-semibold text-slate-500 uppercase">Total Transactions</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $transactionCount }}</h3>
            </div>
        </div>

        <!-- Category Breakdown Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Expense by Category Table -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-base mb-4">Expenses by Category</h3>
                @if($expenseByCategory->isEmpty())
                    <p class="text-sm text-slate-500 text-center py-6">No expense records found in this range.</p>
                @else
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase">
                                <th class="py-2">Category</th>
                                <th class="py-2 text-center">Count</th>
                                <th class="py-2 text-right">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($expenseByCategory as $item)
                                <tr>
                                    <td class="py-3 font-semibold text-slate-800">{{ $item->category }}</td>
                                    <td class="py-3 text-center text-slate-500">{{ $item->count }}</td>
                                    <td class="py-3 text-right text-rose-600 font-bold">৳ {{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Income by Category Table -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <h3 class="font-bold text-slate-900 text-base mb-4">Income by Category</h3>
                @if($incomeByCategory->isEmpty())
                    <p class="text-sm text-slate-500 text-center py-6">No income records found in this range.</p>
                @else
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase">
                                <th class="py-2">Category</th>
                                <th class="py-2 text-center">Count</th>
                                <th class="py-2 text-right">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @foreach($incomeByCategory as $item)
                                <tr>
                                    <td class="py-3 font-semibold text-slate-800">{{ $item->category }}</td>
                                    <td class="py-3 text-center text-slate-500">{{ $item->count }}</td>
                                    <td class="py-3 text-right text-emerald-600 font-bold">৳ {{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
