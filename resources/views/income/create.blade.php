<x-app-layout>
    <x-slot name="header">
        Add Income
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="mb-6 pb-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Record New Income</h2>
                    <p class="text-sm text-slate-500">Fill in details for your received income.</p>
                </div>
                <a href="{{ route('income.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Cancel</a>
            </div>

            <form method="POST" action="{{ route('income.store') }}" class="space-y-5">
                @csrf

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Amount (৳) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 font-bold">৳</span>
                        <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" placeholder="0.00" required class="pl-8 w-full rounded-xl border-slate-200 text-slate-900 font-semibold focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                    @error('amount') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Category <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="" disabled selected>Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Description</label>
                    <input type="text" name="description" value="{{ old('description') }}" placeholder="Short summary (e.g. Monthly Salary)" class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                    @error('description') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <!-- Date & Payment Method Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Date <span class="text-rose-500">*</span></label>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                        @error('date') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Payment Method</label>
                        <select name="payment_method" class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">Select Method</option>
                            @foreach($paymentMethods as $pm)
                                <option value="{{ $pm }}" {{ old('payment_method') === $pm ? 'selected' : '' }}>{{ $pm }}</option>
                            @endforeach
                        </select>
                        @error('payment_method') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Note -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Additional Note</label>
                    <textarea name="note" rows="3" placeholder="Optional notes or context..." class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-emerald-500 focus:ring-emerald-500">{{ old('note') }}</textarea>
                    @error('note') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex items-center justify-end space-x-3">
                    <a href="{{ route('income.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-md shadow-emerald-600/20">
                        Save Income
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
