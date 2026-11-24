<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description ?? 'Sunnah Archery Community — Komunitas panahan berbasis sunnah di Batam' }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}Sunnah Archery Community</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-sac.jpg') }}">

    {{-- Google Fonts: Plus Jakarta Sans & Urbanist --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Urbanist:wght@500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite (Tailwind + Alpine) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes sac-ticker {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        .animate-sac-ticker {
            display: flex;
            width: max-content;
            animation: sac-ticker 26s linear infinite;
        }
        .animate-sac-ticker:hover {
            animation-play-state: paused;
        }
        .reveal-item {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-item.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    @stack('styles')
</head>
<body class="bg-[#FAFAFB] text-[#1F2937] font-jakarta antialiased selection:bg-primary-100 selection:text-primary-900">

    {{-- ===== STICKY NAVBAR ===== --}}
    <nav x-data="{ open: false, activeSection: '{{ request()->routeIs('beranda') ? 'beranda' : (request()->routeIs('profil') ? 'profil' : (request()->routeIs('dokumentasi') ? 'dokumentasi' : '')) }}' }"
         @scroll-section.window="activeSection = $event.detail"
         class="bg-white/95 backdrop-blur-md sticky top-0 z-50 border-b border-[#E5E7EB]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo Kecil SAC --}}
                <a href="{{ route('beranda') }}" class="flex items-center gap-2.5 group">
                    <img src="{{ asset('images/logo-sac.jpg') }}" alt="Logo SAC" class="h-9 w-auto object-contain rounded-lg">
                    <div class="leading-tight">
                        <span class="font-extrabold text-[#1F2937] text-base tracking-tight block">SAC</span>
                        <span class="text-[10px] text-[#6B7280] font-medium tracking-wider uppercase block">Archery Club</span>
                    </div>
                </a>

                {{-- Desktop Nav Links (Active highlight on scroll) --}}
                <div class="hidden md:flex items-center gap-1 lg:gap-2">
                    <a href="{{ request()->routeIs('beranda') ? '#beranda' : route('beranda') }}"
                       :class="activeSection === 'beranda' ? 'text-primary-600 bg-primary-50 font-semibold' : 'text-[#6B7280] hover:text-primary-600 hover:bg-primary-50/50'"
                       class="px-3.5 py-1.5 rounded-xl text-sm font-medium transition-all">
                        Beranda
                    </a>
                    <a href="{{ request()->routeIs('beranda') ? '#profil' : route('profil') }}"
                       :class="activeSection === 'profil' ? 'text-primary-600 bg-primary-50 font-semibold' : 'text-[#6B7280] hover:text-primary-600 hover:bg-primary-50/50'"
                       class="px-3.5 py-1.5 rounded-xl text-sm font-medium transition-all">
                        Profil
                    </a>
                    <a href="{{ request()->routeIs('beranda') ? '#jadwal' : route('beranda') . '#jadwal' }}"
                       :class="activeSection === 'jadwal' ? 'text-primary-600 bg-primary-50 font-semibold' : 'text-[#6B7280] hover:text-primary-600 hover:bg-primary-50/50'"
                       class="px-3.5 py-1.5 rounded-xl text-sm font-medium transition-all">
                        Jadwal
                    </a>
                    <a href="{{ request()->routeIs('beranda') ? '#kejuaraan' : route('beranda') . '#kejuaraan' }}"
                       :class="activeSection === 'kejuaraan' ? 'text-primary-600 bg-primary-50 font-semibold' : 'text-[#6B7280] hover:text-primary-600 hover:bg-primary-50/50'"
                       class="px-3.5 py-1.5 rounded-xl text-sm font-medium transition-all">
                        Kejuaraan
                    </a>
                    <a href="{{ request()->routeIs('beranda') ? '#dokumentasi' : route('dokumentasi') }}"
                       :class="activeSection === 'dokumentasi' ? 'text-primary-600 bg-primary-50 font-semibold' : 'text-[#6B7280] hover:text-primary-600 hover:bg-primary-50/50'"
                       class="px-3.5 py-1.5 rounded-xl text-sm font-medium transition-all">
                        Dokumentasi
                    </a>
                </div>

                {{-- Desktop Action Buttons: Daftar (Ungu) & Masuk (Outline) --}}
                <div class="hidden md:flex items-center gap-2.5">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('anggota.dashboard') }}"
                           class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="border border-primary-300 text-primary-700 hover:bg-primary-50 text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('daftar') }}"
                           class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors shadow-sm">
                            Daftar
                        </a>
                    @endauth
                </div>

                {{-- Mobile Hamburger --}}
                <button @click="open = !open" class="md:hidden p-2 rounded-xl text-[#6B7280] hover:bg-primary-50 hover:text-primary-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open" x-transition class="md:hidden border-t border-[#E5E7EB] bg-white px-4 py-4 space-y-2">
            <a href="{{ request()->routeIs('beranda') ? '#beranda' : route('beranda') }}" @click="open = false" class="block px-3 py-2 rounded-xl text-sm font-medium text-[#1F2937] hover:bg-primary-50">Beranda</a>
            <a href="{{ request()->routeIs('beranda') ? '#profil' : route('profil') }}" @click="open = false" class="block px-3 py-2 rounded-xl text-sm font-medium text-[#1F2937] hover:bg-primary-50">Profil</a>
            <a href="{{ request()->routeIs('beranda') ? '#jadwal' : route('beranda') . '#jadwal' }}" @click="open = false" class="block px-3 py-2 rounded-xl text-sm font-medium text-[#1F2937] hover:bg-primary-50">Jadwal</a>
            <a href="{{ request()->routeIs('beranda') ? '#kejuaraan' : route('beranda') . '#kejuaraan' }}" @click="open = false" class="block px-3 py-2 rounded-xl text-sm font-medium text-[#1F2937] hover:bg-primary-50">Kejuaraan</a>
            <a href="{{ request()->routeIs('beranda') ? '#dokumentasi' : route('dokumentasi') }}" @click="open = false" class="block px-3 py-2 rounded-xl text-sm font-medium text-[#1F2937] hover:bg-primary-50">Dokumentasi</a>

            <div class="pt-2 border-t border-[#E5E7EB] grid grid-cols-2 gap-2">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('anggota.dashboard') }}"
                       class="col-span-2 text-center bg-primary-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-center border border-primary-300 text-primary-700 text-sm font-semibold px-4 py-2.5 rounded-xl">
                        Masuk
                    </a>
                    <a href="{{ route('daftar') }}"
                       class="text-center bg-primary-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ===== MAIN CONTENT ===== --}}
    <main>
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-white border-t border-[#E5E7EB] text-[#6B7280] mt-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                {{-- Brand & Deskripsi --}}
                <div class="md:col-span-5 space-y-3">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo-sac.jpg') }}" alt="Logo SAC" class="h-10 w-auto object-contain rounded-lg">
                        <div>
                            <span class="text-[#1F2937] font-bold block leading-tight">Sunnah Archery Community</span>
                            <span class="text-xs text-primary-600 font-medium">Batam Archery Club</span>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed max-w-sm">
                        Komunitas panahan berbasis sunnah di Kota Batam. Membina fokus, teknik memanah yang terukur, dan prestasi kejuaraan dalam suasana kekeluargaan.
                    </p>
                </div>

                {{-- Navigasi Cepat --}}
                <div class="md:col-span-3">
                    <h4 class="text-[#1F2937] text-sm font-bold mb-3">Navigasi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('beranda') }}" class="hover:text-primary-600 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('profil') }}" class="hover:text-primary-600 transition-colors">Profil Pelatih & Klub</a></li>
                        <li><a href="{{ route('beranda') }}#jadwal" class="hover:text-primary-600 transition-colors">Jadwal Latihan</a></li>
                        <li><a href="{{ route('beranda') }}#kejuaraan" class="hover:text-primary-600 transition-colors">Kejuaraan & Prestasi</a></li>
                        <li><a href="{{ route('dokumentasi') }}" class="hover:text-primary-600 transition-colors">Dokumentasi Galeri</a></li>
                    </ul>
                </div>

                {{-- Alamat, Instagram, & Tombol WhatsApp Admin --}}
                <div class="md:col-span-4 space-y-3">
                    <h4 class="text-[#1F2937] text-sm font-bold mb-3">Lokasi & Kontak Resmi</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li class="flex items-start gap-2">
                            <span>📍</span>
                            <span>Lapangan Archery SAC, Kota Batam, Kepulauan Riau</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span>📸</span>
                            <a href="https://instagram.com/sunnah_archery_community" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium hover:underline">
                                @sunnah_archery_community
                            </a>
                        </li>
                    </ul>
                    <div class="pt-2">
                        <a href="https://wa.me/{{ env('SAC_ADMIN_WA', '628123456789') }}" target="_blank"
                           class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm">
                            <span>💬</span> Hubungi WhatsApp Admin
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-[#E5E7EB] mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-[#6B7280] gap-2">
                <span>© {{ date('Y') }} Sunnah Archery Community Batam.</span>
                <span>TA Dita Aflaha — Politeknik Negeri Batam</span>
            </div>
        </div>
    </footer>

    {{-- Script Ringan: Scroll Reveal & Active Navbar Highlight --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Animasi muncul halus saat scroll
            const revealEls = document.querySelectorAll('.reveal-item');
            if ('IntersectionObserver' in window) {
                const revealObs = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            revealObs.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.12 });
                revealEls.forEach(el => revealObs.observe(el));

                // 2. Highlight menu aktif saat scroll di Beranda
                const sections = document.querySelectorAll('section[data-nav-section]');
                if (sections.length > 0) {
                    const navObs = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                window.dispatchEvent(new CustomEvent('scroll-section', {
                                    detail: entry.target.getAttribute('data-nav-section')
                                }));
                            }
                        });
                    }, { rootMargin: '-30% 0px -55% 0px' });
                    sections.forEach(sec => navObs.observe(sec));
                }
            } else {
                revealEls.forEach(el => el.classList.add('is-visible'));
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
