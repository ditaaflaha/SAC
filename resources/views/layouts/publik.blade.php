<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? 'Sunnah Archery Community — Komunitas panahan berbasis sunnah di Batam' }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}Sunnah Archery Community</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-sac.jpg') }}">

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite (Tailwind + Alpine) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-gray-50 font-jakarta antialiased">

    {{-- ===== NAVBAR ===== --}}
    <nav x-data="{ open: false }" class="bg-white/90 backdrop-blur sticky top-0 z-50 shadow-sm border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('beranda') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo-sac.jpg') }}" alt="Logo SAC" class="w-10 h-10 object-contain rounded-lg">
                    <span class="font-extrabold text-gray-800 tracking-tight">SAC</span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('beranda') }}"
                       class="text-sm font-medium {{ request()->routeIs('beranda') ? 'text-emerald-600' : 'text-gray-600 hover:text-emerald-600' }} transition-colors">
                        Beranda
                    </a>
                    <a href="{{ route('profil') }}"
                       class="text-sm font-medium {{ request()->routeIs('profil') ? 'text-emerald-600' : 'text-gray-600 hover:text-emerald-600' }} transition-colors">
                        Profil SAC
                    </a>
                    <a href="{{ route('dokumentasi') }}"
                       class="text-sm font-medium {{ request()->routeIs('dokumentasi') ? 'text-emerald-600' : 'text-gray-600 hover:text-emerald-600' }} transition-colors">
                        Dokumentasi
                    </a>

                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('anggota.dashboard') }}"
                           class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-5 py-2 rounded-xl transition-colors">
                            Masuk
                        </a>
                    @endauth
                </div>

                {{-- Mobile Hamburger --}}
                <button @click="open = !open" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open" x-transition class="md:hidden border-t border-gray-100 bg-white px-4 py-3 space-y-2">
            <a href="{{ route('beranda') }}" class="block py-2 text-sm font-medium text-gray-700">Beranda</a>
            <a href="{{ route('profil') }}" class="block py-2 text-sm font-medium text-gray-700">Profil SAC</a>
            <a href="{{ route('dokumentasi') }}" class="block py-2 text-sm font-medium text-gray-700">Dokumentasi</a>
            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('anggota.dashboard') }}"
                   class="block py-2 text-sm font-medium text-emerald-600">Dashboard</a>
            @else
                <a href="{{ route('login') }}"
                   class="block mt-1 text-center bg-emerald-500 text-white text-sm font-semibold px-4 py-2 rounded-xl">
                    Masuk
                </a>
            @endauth
        </div>
    </nav>

    {{-- ===== MAIN CONTENT ===== --}}
    <main>
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-gray-900 text-gray-400 mt-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <img src="{{ asset('images/logo-sac.jpg') }}" alt="Logo SAC" class="w-9 h-9 object-contain rounded-lg bg-white p-0.5">
                        <span class="text-white font-bold">Sunnah Archery Community</span>
                    </div>
                    <p class="text-sm leading-relaxed">Komunitas panahan berbasis sunnah di Batam. Bersama kita tumbuh dan berprestasi.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Tautan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('beranda') }}" class="hover:text-emerald-400 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('profil') }}" class="hover:text-emerald-400 transition-colors">Profil SAC</a></li>
                        <li><a href="{{ route('dokumentasi') }}" class="hover:text-emerald-400 transition-colors">Dokumentasi</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-400 transition-colors">Masuk Portal</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        <li>📍 Batam, Kepulauan Riau</li>
                        <li>📅 Latihan: Setiap Ahad, 07.00 WIB</li>
                        <li>
                            <a href="https://wa.me/{{ env('SAC_ADMIN_WA', '628123456789') }}"
                               class="hover:text-emerald-400 transition-colors">
                                💬 Hubungi via WhatsApp
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-6 text-center text-xs">
                © {{ date('Y') }} Sunnah Archery Community. Dibuat untuk TA Dita Aflaha — Politeknik Negeri Batam.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
