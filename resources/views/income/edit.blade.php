<x-app-layout>
    <x-slot name="header">
        Edit Income
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Edit Income Entry</h2>
                    <p class="text-sm text-slate-500">Update transaction details.</p>
                </div>
                <a href="{{ route('income.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Cancel</a>
            </div>

            <form method="POST" action="{{ route('income.update', $income->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Amount (৳) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 font-bold">৳</span>
                        <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount', $income->amount) }}" required class="pl-8 w-full rounded-xl border-slate-200 text-slate-900 font-semibold focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    @error('amount') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Category <span class="text-rose-500">*</span></label>
                    <input type="text" name="category" list="category-list" value="{{ old('category', $income->category) }}" placeholder="Type category name..." required class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                    <datalist id="category-list">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}"></option>
                        @endforeach
                    </datalist>
                    @error('category') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Description</label>
                    <input type="text" name="description" value="{{ old('description', $income->description) }}" class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                    @error('description') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Date & Payment Method Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="date" value="{{ old('date', $income->date ? $income->date->format('Y-m-d') : '') }}" required class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                        @error('date') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Payment Method</label>
                        <input type="text" name="payment_method" list="payment-method-list" value="{{ old('payment_method', $income->payment_method) }}" placeholder="e.g. Cash, Bank..." class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                        <datalist id="payment-method-list">
                            @foreach($paymentMethods as $pm)
                                <option value="{{ $pm }}"></option>
                            @endforeach
                        </datalist>
                        @error('payment_method') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Note -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Additional Note</label>
                    <textarea name="note" rows="3" class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">{{ old('note', $income->note) }}</textarea>
                    @error('note') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex items-center justify-end space-x-3">
                    <a href="{{ route('income.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md">
                        Update Income
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
