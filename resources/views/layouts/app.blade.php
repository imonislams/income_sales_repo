<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', mobileOpen: false }" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FinancePro') }} - Personal Finance</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="h-full antialiased text-slate-800 bg-slate-50">
        <div class="min-h-screen flex bg-slate-50">
            <!-- Mobile Sidebar Overlay -->
            <div x-cloak x-show="mobileOpen" @click="mobileOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

            <!-- Sidebar -->
            <aside
                :class="{ 'w-64': !sidebarCollapsed || mobileOpen, 'w-20': sidebarCollapsed && !mobileOpen, 'translate-x-0': mobileOpen, '-translate-x-full lg:translate-x-0': !mobileOpen }"
                class="fixed inset-y-0 left-0 z-50 flex flex-col bg-slate-900 text-slate-300 transition-all duration-300 ease-in-out border-r border-slate-800"
            >
                <!-- Brand Header -->
                <div class="flex items-center justify-between h-16 px-3 border-b border-slate-800">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 overflow-hidden">
                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-600 text-white font-bold text-xl shadow-lg shadow-indigo-600/30 flex-shrink-0">
                            ৳
                        </div>
                        <span x-show="!sidebarCollapsed || mobileOpen" class="font-bold text-lg text-white tracking-tight whitespace-nowrap">FinancePro</span>
                    </a>
                    <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)" class="hidden lg:flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                        <svg class="w-5 h-5 transform transition-transform duration-300" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button @click="mobileOpen = false" class="lg:hidden p-1.5 text-slate-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Navigation Menu -->
                <div class="flex-1 overflow-y-auto px-3 py-4 space-y-5">
                    <div>
                        <div x-show="!sidebarCollapsed || mobileOpen" class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Main Menu</div>
                        <nav class="space-y-1">
                            <!-- Dashboard -->
                            <a href="{{ route('dashboard') }}" :class="{ 'justify-center px-0': sidebarCollapsed && !mobileOpen, 'px-3': !sidebarCollapsed || mobileOpen }" class="flex items-center py-2.5 rounded-xl font-medium text-sm transition-all group relative {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                <span x-show="!sidebarCollapsed || mobileOpen" class="ml-3">Dashboard</span>
                                <span x-show="sidebarCollapsed && !mobileOpen" class="absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-md shadow-xl border border-slate-700/80 opacity-0 pointer-events-none group-hover:opacity-100 transition-all duration-150 z-50 whitespace-nowrap">Dashboard</span>
                            </a>

                            <!-- Analytics -->
                            <a href="{{ route('analytics.index') }}" class="flex items-center px-3 py-2.5 rounded-xl font-medium text-sm transition-all group relative {{ request()->routeIs('analytics.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-5 h-5 flex-shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span x-show="!sidebarCollapsed" class="ml-3">📊 Analytics</span>
                                <span x-show="sidebarCollapsed" class="absolute left-full rounded-md px-2 py-1 ml-6 bg-slate-800 text-white text-xs font-semibold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-50 pointer-events-none shadow-lg">📊 Analytics</span>
                            </a>

                            <!-- Income -->
                            <a href="{{ route('income.index') }}" :class="{ 'justify-center px-0': sidebarCollapsed && !mobileOpen, 'px-3': !sidebarCollapsed || mobileOpen }" class="flex items-center py-2.5 rounded-xl font-medium text-sm transition-all group relative {{ request()->routeIs('income.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-5 h-5 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                <span x-show="!sidebarCollapsed || mobileOpen" class="ml-3">Income</span>
                                <span x-show="sidebarCollapsed && !mobileOpen" class="absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-md shadow-xl border border-slate-700/80 opacity-0 pointer-events-none group-hover:opacity-100 transition-all duration-150 z-50 whitespace-nowrap">Income</span>
                            </a>

                            <!-- Expense -->
                            <a href="{{ route('expense.index') }}" :class="{ 'justify-center px-0': sidebarCollapsed && !mobileOpen, 'px-3': !sidebarCollapsed || mobileOpen }" class="flex items-center py-2.5 rounded-xl font-medium text-sm transition-all group relative {{ request()->routeIs('expense.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-5 h-5 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                <span x-show="!sidebarCollapsed || mobileOpen" class="ml-3">Expense</span>
                                <span x-show="sidebarCollapsed && !mobileOpen" class="absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-md shadow-xl border border-slate-700/80 opacity-0 pointer-events-none group-hover:opacity-100 transition-all duration-150 z-50 whitespace-nowrap">Expense</span>
                            </a>

                            <!-- Transactions -->
                            <a href="{{ route('transactions.index') }}" :class="{ 'justify-center px-0': sidebarCollapsed && !mobileOpen, 'px-3': !sidebarCollapsed || mobileOpen }" class="flex items-center py-2.5 rounded-xl font-medium text-sm transition-all group relative {{ request()->routeIs('transactions.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                <span x-show="!sidebarCollapsed || mobileOpen" class="ml-3">Transactions</span>
                                <span x-show="sidebarCollapsed && !mobileOpen" class="absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-md shadow-xl border border-slate-700/80 opacity-0 pointer-events-none group-hover:opacity-100 transition-all duration-150 z-50 whitespace-nowrap">Transactions</span>
                            </a>
                        </nav>
                    </div>

                    <div class="pt-2">
                        <div x-show="!sidebarCollapsed || mobileOpen" class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Management</div>
                        <div x-show="sidebarCollapsed && !mobileOpen" class="my-2 border-t border-slate-800/80"></div>
                        <nav class="space-y-1">
                            <!-- Reports -->
                            <a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2.5 rounded-xl font-medium text-sm transition-all group relative {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span x-show="!sidebarCollapsed" class="ml-3">Reports</span>
                                <span x-show="sidebarCollapsed" class="absolute left-full rounded-md px-2 py-1 ml-6 bg-slate-800 text-white text-xs font-semibold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-50 pointer-events-none shadow-lg">Reports</span>
                            <a href="{{ route('reports.index') }}" :class="{ 'justify-center px-0': sidebarCollapsed && !mobileOpen, 'px-3': !sidebarCollapsed || mobileOpen }" class="flex items-center py-2.5 rounded-xl font-medium text-sm transition-all group relative {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <span x-show="!sidebarCollapsed || mobileOpen" class="ml-3">Reports</span>
                                <span x-show="sidebarCollapsed && !mobileOpen" class="absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-md shadow-xl border border-slate-700/80 opacity-0 pointer-events-none group-hover:opacity-100 transition-all duration-150 z-50 whitespace-nowrap">Reports</span>
                            </a>

                            <!-- Categories -->
                            <a href="{{ route('categories.index') }}" :class="{ 'justify-center px-0': sidebarCollapsed && !mobileOpen, 'px-3': !sidebarCollapsed || mobileOpen }" class="flex items-center py-2.5 rounded-xl font-medium text-sm transition-all group relative {{ request()->routeIs('categories.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M11 7h8M11 11h8M11 15h8"></path></svg>
                                <span x-show="!sidebarCollapsed || mobileOpen" class="ml-3">Categories</span>
                                <span x-show="sidebarCollapsed && !mobileOpen" class="absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-md shadow-xl border border-slate-700/80 opacity-0 pointer-events-none group-hover:opacity-100 transition-all duration-150 z-50 whitespace-nowrap">Categories</span>
                            </a>

                            <!-- Payment Methods -->
                            <a href="{{ route('payment-methods.index') }}" :class="{ 'justify-center px-0': sidebarCollapsed && !mobileOpen, 'px-3': !sidebarCollapsed || mobileOpen }" class="flex items-center py-2.5 rounded-xl font-medium text-sm transition-all group relative {{ request()->routeIs('payment-methods.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span x-show="!sidebarCollapsed || mobileOpen" class="ml-3">Payment Methods</span>
                                <span x-show="sidebarCollapsed && !mobileOpen" class="absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-md shadow-xl border border-slate-700/80 opacity-0 pointer-events-none group-hover:opacity-100 transition-all duration-150 z-50 whitespace-nowrap">Payment Methods</span>
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Footer Account Menu -->
                <div class="p-3 border-t border-slate-800">
                    <nav class="space-y-1">
                        <a href="{{ route('profile.edit') }}" :class="{ 'justify-center px-0': sidebarCollapsed && !mobileOpen, 'px-3': !sidebarCollapsed || mobileOpen }" class="flex items-center py-2.5 rounded-xl font-medium text-sm text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all group relative {{ request()->routeIs('profile.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-show="!sidebarCollapsed || mobileOpen" class="ml-3">Settings</span>
                            <span x-show="sidebarCollapsed && !mobileOpen" class="absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-md shadow-xl border border-slate-700/80 opacity-0 pointer-events-none group-hover:opacity-100 transition-all duration-150 z-50 whitespace-nowrap">Settings</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" :class="{ 'justify-center px-0': sidebarCollapsed && !mobileOpen, 'px-3': !sidebarCollapsed || mobileOpen }" class="w-full flex items-center py-2.5 rounded-xl font-medium text-sm text-rose-400 hover:text-rose-300 hover:bg-slate-800/60 transition-all group relative">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span x-show="!sidebarCollapsed || mobileOpen" class="ml-3">Logout</span>
                                <span x-show="sidebarCollapsed && !mobileOpen" class="absolute left-full top-1/2 -translate-y-1/2 ml-3 px-2.5 py-1.5 bg-slate-900 text-white text-xs font-semibold rounded-md shadow-xl border border-slate-700/80 opacity-0 pointer-events-none group-hover:opacity-100 transition-all duration-150 z-50 whitespace-nowrap">Logout</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div :class="{ 'lg:pl-64': !sidebarCollapsed, 'lg:pl-20': sidebarCollapsed }" class="flex-1 flex flex-col transition-all duration-300 ease-in-out min-w-0">
                <!-- Topbar -->
                <header class="sticky top-0 z-30 flex items-center justify-between h-16 px-4 sm:px-6 bg-white/80 backdrop-blur-md border-b border-slate-200/80 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-slate-900 leading-tight">
                                {{ $header ?? 'Personal Finance' }}
                            </h1>
                            <p class="text-xs text-slate-500 hidden sm:block">Manage your money smarter</p>
                        </div>
                    </div>

                    <!-- User Profile Dropdown / Initial -->
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-3 text-right">
                            <div class="hidden sm:block">
                                <p class="text-sm font-semibold text-slate-800 leading-none">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5 leading-none">{{ Auth::user()->email }}</p>
                            </div>
                            <div class="w-9 h-9 rounded-xl bg-indigo-100 border border-indigo-200 text-indigo-700 flex items-center justify-center font-bold text-sm shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Dynamic Page Content -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                    <x-flash-messages />
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
