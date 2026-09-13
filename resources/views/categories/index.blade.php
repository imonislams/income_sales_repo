<x-app-layout>
    <x-slot name="header">
        Categories
    </x-slot>

    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Manage Categories</h2>
                <p class="text-sm text-slate-500">Organize your incomes and expenses by custom categories.</p>
            </div>
            <a href="{{ route('categories.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md transition-all self-start sm:self-auto">
                + Add Category
            </a>
        </div>

        <!-- Filter Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <form method="GET" action="{{ route('categories.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search category name..." class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="w-full sm:w-48">
                    <select name="type" class="w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Types</option>
                        <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-sm rounded-xl transition-colors">Search</button>
                    <a href="{{ route('categories.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm rounded-xl transition-colors">Reset</a>
                </div>
            </form>
        </div>

        <!-- Categories Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-100">
                            <th class="py-3.5 px-6">Name</th>
                            <th class="py-3.5 px-6">Type</th>
                            <th class="py-3.5 px-6">Scope</th>
                            <th class="py-3.5 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        @foreach($categories as $category)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    {{ $category->name }}
                                </td>
                                <td class="py-4 px-6">
                                    @if($category->type === 'income')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Income
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            Expense
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-500">
                                    @if($category->is_default)
                                        <span class="px-2 py-0.5 rounded bg-slate-100 font-medium">Default System</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 font-medium">Custom</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('categories.edit', $category->id) }}" class="p-1.5 text-slate-400 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        @if(!$category->is_default)
                                            <form method="POST" action="{{ route('categories.destroy', $category->id) }}" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
