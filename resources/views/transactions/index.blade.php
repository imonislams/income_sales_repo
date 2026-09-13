<x-app-layout>
    <x-slot name="header">
        All Transactions
    </x-slot>

    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">All Financial Transactions</h2>
                <p class="text-sm text-slate-500">Comprehensive log of all incomes and expenses.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('income.create') }}" class="inline-flex items-center px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm">
                    + Add Income
                </a>
                <a href="{{ route('expense.create') }}" class="inline-flex items-center px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-sm">
                    + Add Expense
                </a>
            </div>
        </div>

        <!-- Filter & Search Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Description, note..." class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Type</label>
                    <select name="type" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Types</option>
                        <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
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
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Payment Method</label>
                    <select name="payment_method" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Methods</option>
                        @foreach($paymentMethods as $pm)
                            <option value="{{ $pm }}" {{ request('payment_method') === $pm ? 'selected' : '' }}>{{ $pm }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Sort By</label>
                    <select name="sort" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest First</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="amount_high" {{ request('sort') === 'amount_high' ? 'selected' : '' }}>Amount: High to Low</option>
                        <option value="amount_low" {{ request('sort') === 'amount_low' ? 'selected' : '' }}>Amount: Low to High</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-xl transition-colors">
                        Apply Filter
                    </button>
                    <a href="{{ route('transactions.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            @if($transactions->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h4 class="text-base font-bold text-slate-800">No transactions found</h4>
                    <p class="text-sm text-slate-500 max-w-sm mx-auto mt-1">Add your first income or expense to start tracking your finances.</p>
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
                                <th class="py-3.5 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                            @foreach($transactions as $transaction)
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
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('transactions.show', $transaction->id) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <a href="{{ route('transactions.edit', $transaction->id) }}" class="p-1.5 text-slate-400 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form method="POST" action="{{ route('transactions.destroy', $transaction->id) }}" onsubmit="return confirm('Are you sure you want to delete this transaction?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
