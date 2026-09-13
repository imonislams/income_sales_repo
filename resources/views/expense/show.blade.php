<x-app-layout>
    <x-slot name="header">
        Expense Details
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
            <div class="flex items-center justify-between pb-6 border-b border-slate-100">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 uppercase tracking-wider">
                        Expense
                    </span>
                    <h2 class="text-3xl font-extrabold text-rose-600 mt-2">-৳ {{ number_format($expense->amount, 2) }}</h2>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('expense.edit', $expense->id) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm transition-colors">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('expense.destroy', $expense->id) }}" onsubmit="return confirm('Are you sure you want to delete this record?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-sm transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Category</p>
                    <p class="font-bold text-slate-800 mt-0.5">{{ $expense->category }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Date</p>
                    <p class="font-bold text-slate-800 mt-0.5">{{ $expense->date ? $expense->date->format('M d, Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Payment Method</p>
                    <p class="font-bold text-slate-800 mt-0.5">{{ $expense->payment_method ?: 'Not specified' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Created At</p>
                    <p class="font-bold text-slate-800 mt-0.5">{{ $expense->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase">Description</p>
                <p class="text-slate-800 font-medium mt-1 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    {{ $expense->description ?: 'No description provided.' }}
                </p>
            </div>

            @if($expense->note)
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Note</p>
                    <p class="text-slate-700 text-sm mt-1 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        {{ $expense->note }}
                    </p>
                </div>
            @endif

            <div class="pt-4 border-t border-slate-100 text-right">
                <a href="{{ route('expense.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">← Back to Expense List</a>
            </div>
        </div>
    </div>
</x-app-layout>
