<x-app-layout>
    <x-slot name="header">
        Create Category
    </x-slot>

    <div class="max-w-md mx-auto">
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100">New Financial Category</h2>

            <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Category Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Freelance Consulting" required class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Type <span class="text-rose-500">*</span></label>
                    <select name="type" required class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="income" {{ old('type') === 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                    @error('type') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex items-center justify-end space-x-3">
                    <a href="{{ route('categories.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
