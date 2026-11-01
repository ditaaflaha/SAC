<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' — ' : '' }}Dashboard Anggota SAC</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-sac.jpg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 font-jakarta antialiased h-full">

<div class="flex h-full" x-data="{ sidebarOpen: false }">

    {{-- ===== SIDEBAR (Desktop) ===== --}}
    <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-white border-r border-gray-100 shadow-sm z-30">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100">
            <img src="{{ asset('images/logo-sac.jpg') }}" alt="Logo SAC" class="w-10 h-10 object-contain rounded-lg flex-shrink-0">
            <div>
                <div class="font-extrabold text-gray-800 text-sm leading-tight">SAC</div>
                <div class="text-xs text-gray-500 leading-tight">Anggota</div>
            </div>
        </div>

        {{-- Nav Items --}}
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            @php
                $navItems = [
                    ['route' => 'anggota.dashboard',  'icon' => '🏠', 'label' => 'Dashboard'],
                    ['route' => 'anggota.jadwal',     'icon' => '📅', 'label' => 'Jadwal & Presensi'],
                    ['route' => 'anggota.skor',       'icon' => '🎯', 'label' => 'Input Skor'],
                    ['route' => 'anggota.progres',    'icon' => '📈', 'label' => 'Progres Saya'],
                    ['route' => 'anggota.leaderboard','icon' => '🏆', 'label' => 'Leaderboard'],
                    ['route' => 'anggota.target',     'icon' => '🎪', 'label' => 'Target & Rekomendasi'],
                    ['route' => 'anggota.iuran',      'icon' => '💳', 'label' => 'Iuran Saya'],
                    ['route' => 'anggota.pengumuman', 'icon' => '📢', 'label' => 'Pengumuman'],
                ];
            @endphp

            @foreach ($navItems as $item)
                @if (Route::has($item['route']))
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                              {{ request()->routeIs($item['route']) ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="text-base">{{ $item['icon'] }}</span>
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>

        {{-- User Info + Logout --}}
        <div class="border-t border-gray-100 px-4 py-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-emerald-700 font-bold text-xs">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-500">Anggota</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left text-sm text-red-500 hover:text-red-600 font-medium px-3 py-2 rounded-xl hover:bg-red-50 transition-colors flex items-center gap-2">
                    <span>🚪</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MOBILE OVERLAY ===== --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/40 z-20 lg:hidden" x-transition></div>

    {{-- ===== MOBILE SIDEBAR ===== --}}
    <aside x-show="sidebarOpen" x-transition:enter="transform transition-transform duration-200"
           x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
           x-transition:leave="transform transition-transform duration-200"
           x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
           class="fixed inset-y-0 left-0 w-72 bg-white shadow-2xl z-30 lg:hidden flex flex-col">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo-sac.jpg') }}" alt="Logo SAC" class="w-9 h-9 object-contain rounded-lg">
                <span class="font-extrabold text-gray-800">SAC</span>
            </div>
            <button @click="sidebarOpen = false" class="p-1 text-gray-500">✕</button>
        </div>
        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
            @foreach ($navItems ?? [] as $item)
                @if (Route::has($item['route']))
                    <a href="{{ route($item['route']) }}" @click="sidebarOpen = false"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
                              {{ request()->routeIs($item['route']) ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span>{{ $item['icon'] }}</span> {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>
        <div class="border-t border-gray-100 px-4 py-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-sm text-red-500 font-medium px-3 py-2 rounded-xl hover:bg-red-50 transition-colors">
                    🚪 Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN AREA ===== --}}
    <div class="flex-1 flex flex-col min-h-screen lg:ml-64">
        {{-- Top Bar --}}
        <header class="sticky top-0 z-10 bg-white border-b border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-4 sm:px-6 h-14">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div class="text-sm font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <span class="text-emerald-700 font-bold text-xs">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mx-4 sm:mx-6 mt-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if (session('info'))
            <div class="mx-4 sm:mx-6 mt-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2">
                ℹ️ {{ session('info') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mx-4 sm:mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm font-medium flex items-center gap-2">
                ❌ {{ session('error') }}
            </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 px-4 sm:px-6 py-6">
            @yield('content')
        </main>

        {{-- Bottom Nav (Mobile) --}}
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 px-2 py-2 z-10">
            <div class="flex justify-around">
                <a href="{{ route('anggota.dashboard') }}"
                   class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl {{ request()->routeIs('anggota.dashboard') ? 'text-emerald-600' : 'text-gray-500' }}">
                    <span class="text-lg">🏠</span>
                    <span class="text-xs font-medium">Home</span>
                </a>
                @if (Route::has('anggota.skor'))
                <a href="{{ route('anggota.skor') }}"
                   class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl {{ request()->routeIs('anggota.skor') ? 'text-emerald-600' : 'text-gray-500' }}">
                    <span class="text-lg">🎯</span>
                    <span class="text-xs font-medium">Skor</span>
                </a>
                @endif
                @if (Route::has('anggota.progres'))
                <a href="{{ route('anggota.progres') }}"
                   class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl {{ request()->routeIs('anggota.progres') ? 'text-emerald-600' : 'text-gray-500' }}">
                    <span class="text-lg">📈</span>
                    <span class="text-xs font-medium">Progres</span>
                </a>
                @endif
                @if (Route::has('anggota.leaderboard'))
                <a href="{{ route('anggota.leaderboard') }}"
                   class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl {{ request()->routeIs('anggota.leaderboard') ? 'text-emerald-600' : 'text-gray-500' }}">
                    <span class="text-lg">🏆</span>
                    <span class="text-xs font-medium">Rank</span>
                </a>
                @endif
                @if (Route::has('anggota.iuran'))
                <a href="{{ route('anggota.iuran') }}"
                   class="flex flex-col items-center gap-0.5 px-3 py-1.5 rounded-xl {{ request()->routeIs('anggota.iuran') ? 'text-emerald-600' : 'text-gray-500' }}">
                    <span class="text-lg">💳</span>
                    <span class="text-xs font-medium">Iuran</span>
                </a>
                @endif
            </div>
        </nav>
        {{-- Padding bawah untuk bottom nav mobile --}}
        <div class="h-16 lg:hidden"></div>
    </div>
</div>

@stack('scripts')
</body>
</html>
