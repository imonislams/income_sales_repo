<x-app-layout>
    <x-slot name="header">
        Create Payment Method
    </x-slot>

    <div class="max-w-md mx-auto">
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100">New Payment Method</h2>

            <form method="POST" action="{{ route('payment-methods.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Payment Method Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Upay, City Bank" required class="w-full rounded-xl border-slate-200 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex items-center justify-end space-x-3">
                    <a href="{{ route('payment-methods.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md">
                        Save Payment Method
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
