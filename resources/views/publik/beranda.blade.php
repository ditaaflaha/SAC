@extends('layouts.publik')

@section('title', 'Beranda')

@section('content')

{{-- ===== HERO ===== --}}
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 text-white">
    {{-- Dekoratif lingkaran blur --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-black/10 rounded-full -translate-x-1/3 translate-y-1/3 blur-2xl"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur text-sm font-semibold px-4 py-2 rounded-full mb-6">
                🏹 Komunitas Panahan Berbasis Sunnah
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                Latih Dirimu,<br>
                <span class="text-yellow-300">Raih Prestasi</span>
            </h1>
            <p class="text-lg text-white/85 leading-relaxed mb-8">
                Sunnah Archery Community (SAC) adalah komunitas panahan di Batam yang berfokus pada pengembangan teknik dan prestasi berbasis nilai-nilai Islam. Latihan rutin setiap Ahad!
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('login') }}"
                   class="bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-bold px-8 py-3.5 rounded-2xl transition-all duration-200 text-center shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    🔐 Masuk Portal Anggota
                </a>
                <a href="{{ route('profil') }}"
                   class="bg-white/20 hover:bg-white/30 backdrop-blur font-semibold px-8 py-3.5 rounded-2xl transition-all duration-200 text-center">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===== PROGRAM LATIHAN SAC ===== --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-3">Program Latihan SAC</h2>
        <p class="text-gray-500">Hubungi admin/pelatih untuk pendaftaran keanggotaan baru</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Trial --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center mb-4 text-2xl">🎯</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Trial</h3>
            <p class="text-gray-500 text-sm mb-4 leading-relaxed">Coba dulu! Cocok buat kamu yang penasaran atau baru pertama kali pegang busur.</p>
            <div class="text-2xl font-extrabold text-amber-600 mb-1">Rp75.000</div>
            <div class="text-xs text-gray-400 mb-5">per pertemuan</div>
            <a href="https://wa.me/{{ env('SAC_ADMIN_WA', '628123456789') }}" target="_blank"
               class="block text-center bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm">
                Hubungi Admin via WA
            </a>
        </div>

        {{-- Private Class --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200 relative">
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                Populer
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center mb-4 text-2xl">🏹</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Private Class</h3>
            <p class="text-gray-500 text-sm mb-4 leading-relaxed">6 pertemuan privat bersama pelatih, cocok buat yang mau belajar lebih intensif dari awal.</p>
            <div class="text-2xl font-extrabold text-emerald-600 mb-1">Rp500.000</div>
            <div class="text-xs text-gray-400 mb-5">untuk 6 pertemuan</div>
            <a href="https://wa.me/{{ env('SAC_ADMIN_WA', '628123456789') }}" target="_blank"
               class="block text-center bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm">
                Hubungi Admin via WA
            </a>
        </div>

        {{-- Join Komunitas --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mb-4 text-2xl">🌟</div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Anggota Reguler</h3>
            <p class="text-gray-500 text-sm mb-4 leading-relaxed">Resmi jadi anggota SAC! Latihan rutin bareng, akses dashboard progres, dan banyak lagi.</p>
            <div class="text-2xl font-extrabold text-blue-600 mb-1">Rp350.000</div>
            <div class="text-xs text-gray-400 mb-1">pendaftaran + iuran Rp40.000/bulan</div>
            <div class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 text-xs font-semibold px-2 py-1 rounded-full mb-4">
                🎁 2 bulan pertama GRATIS iuran!
            </div>
            <a href="https://wa.me/{{ env('SAC_ADMIN_WA', '628123456789') }}" target="_blank"
               class="block text-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm">
                Hubungi Admin via WA
            </a>
        </div>
    </div>
</section>

{{-- ===== PENGUMUMAN TERBARU ===== --}}
@if($pengumuman->isNotEmpty())
<section class="bg-white border-y border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-extrabold text-gray-900">📢 Pengumuman Terbaru</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach ($pengumuman as $item)
                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-colors">
                    <div class="text-xs text-gray-400 mb-2">
                        {{ $item->tanggal->translatedFormat('d F Y') }}
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">{{ $item->judul }}</h3>
                    <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">{{ $item->isi }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== GALERI CUPLIKAN ===== --}}
@if($galeri->isNotEmpty())
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-extrabold text-gray-900">📸 Dokumentasi Kegiatan</h2>
        <a href="{{ route('dokumentasi') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">Lihat semua →</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach ($galeri as $foto)
            <div class="aspect-square bg-emerald-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center">
                <span class="text-5xl">🏹</span>
                {{-- Jika file tersedia: <img src="{{ Storage::url($foto->file) }}" class="w-full h-full object-cover"> --}}
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== CTA AKHIR ===== --}}
<section class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Sudah Jadi Anggota SAC? 🏹</h2>
        <p class="text-white/85 text-lg mb-8">Masuk ke portal anggota untuk mencatat skor tembakan, presensi latihan, dan memantau progresmu!</p>
        <a href="{{ route('login') }}"
           class="bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-bold px-10 py-4 rounded-2xl text-lg transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5 inline-block">
            🔐 Masuk Sekarang
        </a>
    </div>
</section>

@endsection
