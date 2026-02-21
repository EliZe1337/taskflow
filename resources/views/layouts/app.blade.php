<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TaskFlow') — Система управления задачами</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#eff6ff',500:'#3b82f6',600:'#2563eb',700:'#1d4ed8' },
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link { @apply flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-700 hover:text-white transition-all; }
        .sidebar-link.active { @apply bg-blue-600 text-white; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 min-h-screen" x-data="{ sidebarOpen: false }">

{{-- Sidebar --}}
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-800 shadow-xl transform transition-transform duration-200 lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

    {{-- Logo --}}
    <div class="flex items-center gap-3 h-16 px-5 border-b border-slate-700">
        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <span class="text-white font-bold text-lg">TaskFlow</span>
    </div>

    {{-- Navigation --}}
    <nav class="p-3 space-y-1">
        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Дашборд
        </a>

        <a href="{{ route('projects.index') }}"
           class="sidebar-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Проекты
        </a>

        <a href="{{ route('tasks.index') }}"
           class="sidebar-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            Задачи
        </a>

        <a href="/notes"
           class="sidebar-link {{ request()->is('notes*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Заметки
        </a>

        @can('viewAny', App\Models\User::class)
            <a href="{{ route('users.index') }}"
               class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Пользователи
            </a>
        @endcan
    </nav>

    {{-- User block --}}
    <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-slate-700" x-data="{ open: false }">
        <button @click="open = !open" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-700 transition-all">
            <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-8 h-8 rounded-full">
            <div class="flex-1 text-left">
                <div class="text-sm font-medium text-white">{{ auth()->user()->name }}</div>
                <div class="text-xs text-slate-400">{{ auth()->user()->getRoleNames()->first() }}</div>
            </div>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" x-cloak @click.outside="open = false"
             class="mt-1 bg-slate-700 rounded-lg p-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left px-3 py-2 text-sm text-red-400 hover:bg-slate-600 rounded-md transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Выйти
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Overlay for mobile --}}
<div x-show="sidebarOpen" @click="sidebarOpen = false"
     class="fixed inset-0 z-40 bg-black/50 lg:hidden" x-cloak></div>

{{-- Main content --}}
<div class="lg:pl-64 min-h-screen">
    {{-- Top bar --}}
    <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 sticky top-0 z-30">
        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="flex-1 ml-4 lg:ml-0">
            <h1 class="text-lg font-semibold text-slate-800">@yield('header', 'Dashboard')</h1>
        </div>

        <div class="flex items-center gap-3">
            @can('create', App\Models\Task::class)
                <a href="{{ route('tasks.create') }}"
                   class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Новая задача
                </a>
            @endcan
        </div>
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-green-600 hover:text-green-800">✕</button>
        </div>
    @endif

    @if(session('error'))
        <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Page content --}}
    <main class="p-6">
        @yield('content')
    </main>
</div>

</body>
</html>