@extends('layouts.publik')

@section('title', 'Beranda')

@section('content')

{{-- ===== HERO (Gradient halus dari #7C3AED ke #A78BFA) ===== --}}
<section class="relative overflow-hidden bg-gradient-to-r from-primary-600 to-primary-400 text-white">
    {{-- Dekoratif lingkaran blur --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full translate-x-1/3 -translate-y-1/3 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/20 rounded-full -translate-x-1/3 translate-y-1/3 blur-2xl"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur text-sm font-semibold px-4 py-1.5 rounded-full mb-6">
                🏹 Komunitas Panahan Berbasis Sunnah
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-5">
                Latih Dirimu,<br>
                <span class="text-primary-100">Raih Prestasi</span>
            </h1>
            <p class="text-base md:text-lg text-white/90 leading-relaxed mb-8">
                Sunnah Archery Community (SAC) adalah komunitas panahan di Batam yang berfokus pada pengembangan teknik dan prestasi berbasis nilai-nilai Islam. Latihan rutin setiap Ahad!
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('login') }}"
                   class="bg-white text-primary-700 hover:bg-primary-50 font-bold px-7 py-3 rounded-xl transition-all duration-200 text-center shadow-sm">
                    🔐 Masuk Portal Anggota
                </a>
                <a href="{{ route('profil') }}"
                   class="bg-white/20 hover:bg-white/30 backdrop-blur text-white font-semibold px-7 py-3 rounded-xl transition-all duration-200 text-center border border-white/30">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===== PROGRAM LATIHAN SAC ===== --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <span class="text-xs font-bold uppercase tracking-wider text-primary-600 bg-primary-50 px-3 py-1 rounded-full">Program Kami</span>
        <h2 class="text-2xl md:text-3xl font-bold text-[#1F2937] mt-3 mb-2">Program Latihan SAC</h2>
        <p class="text-sm text-[#6B7280]">Hubungi admin/pelatih untuk informasi pendaftaran keanggotaan baru</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Trial --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] p-6 hover:border-primary-200 transition-all duration-200 flex flex-col justify-between">
            <div>
                <div class="w-11 h-11 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center mb-4 text-xl">🎯</div>
                <h3 class="text-lg font-bold text-[#1F2937] mb-1.5">Trial</h3>
                <p class="text-[#6B7280] text-sm mb-5 leading-relaxed">Coba dulu! Cocok buat kamu yang penasaran atau baru pertama kali pegang busur.</p>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-[#1F2937] mb-0.5">Rp75.000</div>
                <div class="text-xs text-[#6B7280] mb-5">per pertemuan</div>
                <a href="https://wa.me/{{ env('SAC_ADMIN_WA', '628123456789') }}" target="_blank"
                   class="block text-center bg-primary-50 hover:bg-primary-100 text-primary-700 font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm">
                    Hubungi Admin via WA
                </a>
            </div>
        </div>

        {{-- Private Class --}}
        <div class="bg-white rounded-2xl shadow-sm border-2 border-primary-300 p-6 transition-all duration-200 relative flex flex-col justify-between">
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-primary-600 text-white text-xs font-semibold px-3 py-0.5 rounded-full">
                Populer
            </div>
            <div>
                <div class="w-11 h-11 bg-primary-100 text-primary-700 rounded-xl flex items-center justify-center mb-4 text-xl">🏹</div>
                <h3 class="text-lg font-bold text-[#1F2937] mb-1.5">Private Class</h3>
                <p class="text-[#6B7280] text-sm mb-5 leading-relaxed">6 pertemuan privat bersama pelatih, cocok buat yang mau belajar lebih intensif dari awal.</p>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-primary-600 mb-0.5">Rp500.000</div>
                <div class="text-xs text-[#6B7280] mb-5">untuk 6 pertemuan</div>
                <a href="https://wa.me/{{ env('SAC_ADMIN_WA', '628123456789') }}" target="_blank"
                   class="block text-center bg-primary-600 hover:bg-primary-700 text-white font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm shadow-sm">
                    Hubungi Admin via WA
                </a>
            </div>
        </div>

        {{-- Anggota Reguler --}}
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] p-6 hover:border-primary-200 transition-all duration-200 flex flex-col justify-between">
            <div>
                <div class="w-11 h-11 bg-accent-100 text-primary-700 rounded-xl flex items-center justify-center mb-4 text-xl">🌟</div>
                <h3 class="text-lg font-bold text-[#1F2937] mb-1.5">Anggota Reguler</h3>
                <p class="text-[#6B7280] text-sm mb-5 leading-relaxed">Resmi jadi anggota SAC! Latihan rutin bareng, akses dashboard progres, dan banyak lagi.</p>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-[#1F2937] mb-0.5">Rp350.000</div>
                <div class="text-xs text-[#6B7280] mb-5">biaya pendaftaran anggota</div>
                <a href="https://wa.me/{{ env('SAC_ADMIN_WA', '628123456789') }}" target="_blank"
                   class="block text-center bg-primary-50 hover:bg-primary-100 text-primary-700 font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm">
                    Hubungi Admin via WA
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===== PENGUMUMAN TERBARU ===== --}}
@if($pengumuman->isNotEmpty())
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-[#1F2937]">📢 Pengumuman Terbaru</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach ($pengumuman as $item)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#E5E7EB] hover:border-primary-200 transition-colors">
                <div class="text-xs font-medium text-primary-600 mb-1.5">
                    {{ $item->tanggal->translatedFormat('d F Y') }}
                </div>
                <h3 class="font-bold text-[#1F2937] mb-2">{{ $item->judul }}</h3>
                <p class="text-sm text-[#6B7280] leading-relaxed line-clamp-3">{{ $item->isi }}</p>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== GALERI CUPLIKAN ===== --}}
@if($galeri->isNotEmpty())
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-[#1F2937]">📸 Dokumentasi Kegiatan</h2>
        <a href="{{ route('dokumentasi') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach ($galeri as $foto)
            <div class="aspect-square bg-white rounded-2xl overflow-hidden border border-[#E5E7EB] shadow-sm flex flex-col items-center justify-center p-4">
                <div class="w-14 h-14 rounded-2xl bg-primary-50 flex items-center justify-center text-3xl mb-2">🏹</div>
                <span class="text-xs font-medium text-[#6B7280] text-center truncate w-full">{{ $foto->judul }}</span>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== CTA AKHIR (Clean Card dengan Aksen Ungu Kalem) ===== --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-8 md:p-12 text-center relative overflow-hidden">
        <div class="pointer-events-none absolute -top-16 -right-16 w-56 h-56 rounded-full bg-primary-100 opacity-60 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-16 -left-16 w-56 h-56 rounded-full bg-accent-100 opacity-60 blur-3xl"></div>
        <div class="relative z-10 max-w-2xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-bold text-[#1F2937] mb-3">Sudah Jadi Anggota SAC? 🏹</h2>
            <p class="text-[#6B7280] text-sm md:text-base mb-6">Masuk ke portal anggota untuk mencatat skor tembakan, presensi latihan, dan memantau grafik progresmu.</p>
            <a href="{{ route('login') }}"
               class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-3 rounded-xl text-sm transition-all duration-200 shadow-sm inline-block">
                Masuk ke Portal Anggota
            </a>
        </div>
    </div>
</section>

@endsection
