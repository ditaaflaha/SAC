@extends('layouts.publik')

@section('title', 'Beranda')

@section('content')

{{-- =====================================================================
     1. HERO SECTION (Clean Modern Sports Club Layout + Soft Purple Accent)
     ===================================================================== --}}
<section id="beranda" data-nav-section="beranda" class="relative overflow-hidden pt-10 pb-16 md:py-20">
    {{-- Dekorasi lembut lingkaran blur pastel --}}
    <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 rounded-full bg-primary-100 opacity-70 blur-3xl"></div>
    <div class="pointer-events-none absolute top-1/2 -right-24 w-80 h-80 rounded-full bg-accent-100 opacity-60 blur-3xl"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            {{-- Kiri: Copywriting & CTA --}}
            <div class="lg:col-span-7 space-y-6 reveal-item">
                <div class="inline-flex items-center gap-2 bg-primary-50 border border-primary-200 text-primary-700 text-xs font-semibold px-3.5 py-1.5 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-primary-600"></span>
                    <span>Batam Archery Club · Berbasis Sunnah & Prestasi</span>
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#1F2937] tracking-tight leading-[1.15]">
                    Latihan Panahan Bareng <span class="text-primary-600">Sunnah Archery Community</span>
                </h1>

                <p class="text-base sm:text-lg text-[#6B7280] leading-relaxed max-w-xl">
                    Bangun fokus, postur, dan akurasi tembakan dengan kurikulum terstruktur. Terbuka untuk pemula yang ingin mencoba trial hingga atlet yang mengejar podium kejuaraan.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-1">
                    <a href="{{ route('daftar', ['pilihan' => 'trial']) }}"
                       class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-7 py-3.5 rounded-xl text-sm text-center transition-all shadow-sm">
                        🎯 Coba Trial
                    </a>
                    <a href="#jadwal"
                       class="bg-primary-50 hover:bg-primary-100 text-primary-700 border border-primary-200 font-semibold px-7 py-3.5 rounded-xl text-sm text-center transition-all">
                        📅 Lihat Jadwal
                    </a>
                </div>
            </div>

            {{-- Kanan: Foto Kegiatan SAC dari tabel galeri (atau placeholder abu bersih) --}}
            <div class="lg:col-span-5 reveal-item">
                @php
                    $heroFoto = $galeri->first();
                    $heroFileExists = $heroFoto && $heroFoto->file && file_exists(storage_path('app/public/' . $heroFoto->file));
                @endphp
                <div class="bg-white p-3 rounded-2xl border border-[#E5E7EB] shadow-sm">
                    @if ($heroFileExists)
                        <div class="aspect-[4/3] rounded-xl overflow-hidden bg-gray-100 relative">
                            <img src="{{ asset('storage/' . $heroFoto->file) }}"
                                 alt="{{ $heroFoto->judul }}"
                                 class="w-full h-full object-cover">
                            <div class="absolute bottom-3 left-3 right-3 bg-white/90 backdrop-blur-sm px-3.5 py-2 rounded-xl border border-[#E5E7EB] flex items-center justify-between text-xs">
                                <span class="font-semibold text-[#1F2937] truncate">{{ $heroFoto->judul }}</span>
                                <span class="text-primary-600 font-mono text-[11px] flex-shrink-0">{{ $heroFoto->tanggal->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="aspect-[4/3] rounded-xl bg-gray-100 border border-[#E5E7EB] flex flex-col items-center justify-center p-6 text-center relative overflow-hidden">
                            <div class="w-16 h-16 rounded-2xl bg-white border border-[#E5E7EB] shadow-sm flex items-center justify-center mb-3">
                                <img src="{{ asset('images/logo-sac.jpg') }}" alt="SAC" class="h-11 w-auto object-contain">
                            </div>
                            <span class="text-sm font-bold text-[#1F2937]">
                                {{ $heroFoto ? $heroFoto->judul : 'Sesi Latihan Rutin SAC Batam' }}
                            </span>
                            <span class="text-xs text-[#6B7280] mt-1">
                                {{ $heroFoto ? $heroFoto->tanggal->translatedFormat('d F Y') : 'Dokumentasi Latihan Mingguan' }}
                            </span>
                            <span class="mt-3 inline-block px-3 py-1 rounded-full bg-white text-[#6B7280] border border-[#E5E7EB] text-[11px] font-medium">
                                📍 Lapangan Archery SAC, Batam
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =====================================================================
     2. TEKS BERJALAN (TICKER) TIPIS
     ===================================================================== --}}
<div class="bg-primary-50 border-y border-primary-100 py-2.5 overflow-hidden">
    <div class="animate-sac-ticker text-xs font-semibold text-primary-700 tracking-wide">
        @for ($i = 0; $i < 6; $i++)
            <span class="mx-6 inline-flex items-center gap-3">
                <span>🏹 Latihan wajib setiap Minggu</span>
                <span class="text-primary-300">•</span>
                <span>Terbuka untuk pemula</span>
                <span class="text-primary-300">•</span>
                <span>Batam</span>
                <span class="text-primary-300">•</span>
            </span>
        @endfor
    </div>
</div>

{{-- =====================================================================
     3. STATISTIK COUNT-UP (Dari Database)
     ===================================================================== --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 reveal-item">
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm text-center">
            <div class="text-xs font-semibold uppercase tracking-wider text-[#6B7280] mb-1">Anggota Aktif</div>
            <div class="text-4xl font-extrabold text-primary-600 font-mono">
                <span class="count-up" data-target="{{ $statistik['anggota_aktif'] ?? 0 }}">0</span>+
            </div>
            <p class="text-xs text-[#6B7280] mt-1.5">Pemanah terdaftar di klub</p>
        </div>

        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm text-center">
            <div class="text-xs font-semibold uppercase tracking-wider text-[#6B7280] mb-1">Total Sesi Latihan</div>
            <div class="text-4xl font-extrabold text-[#1F2937] font-mono">
                <span class="count-up" data-target="{{ $statistik['total_sesi'] ?? 0 }}">0</span>
            </div>
            <p class="text-xs text-[#6B7280] mt-1.5">Sesi latihan & scoring tercatat</p>
        </div>

        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm text-center">
            <div class="text-xs font-semibold uppercase tracking-wider text-[#6B7280] mb-1">Kejuaraan Diikuti</div>
            <div class="text-4xl font-extrabold text-primary-600 font-mono">
                <span class="count-up" data-target="{{ $statistik['total_kejuaraan'] ?? 0 }}">0</span>
            </div>
            <p class="text-xs text-[#6B7280] mt-1.5">Turnamen lokal hingga nasional</p>
        </div>
    </div>
</section>

{{-- =====================================================================
     4. PROFIL PELATIH (#profil)
     ===================================================================== --}}
<section id="profil" data-nav-section="profil" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 sm:p-10 shadow-sm reveal-item">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
            {{-- Foto / Kartu Visual Pelatih --}}
            <div class="md:col-span-4 flex flex-col items-center text-center">
                <div class="w-32 h-32 rounded-2xl bg-primary-50 border border-primary-200 flex items-center justify-center shadow-sm mb-3 overflow-hidden">
                    <span class="text-5xl">🏹</span>
                </div>
                <span class="px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-xs font-semibold">
                    Head Coach SAC
                </span>
            </div>

            {{-- Deskripsi & Pengalaman Pelatih --}}
            <div class="md:col-span-8 space-y-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-primary-600">Profil Pelatih</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#1F2937] mt-1">Coach Revi Andika</h2>
                    <p class="text-xs font-medium text-[#6B7280] mt-0.5">Pelatih Utama Sunnah Archery Community Batam</p>
                </div>

                <p class="text-sm text-[#6B7280] leading-relaxed">
                    Berpengalaman dalam membina pemanah dari tingkat dasar (pemula) hingga atlet kompetisi di kategori Standar Nasional, Recurve, dan Barebow. Setiap anggota dibimbing dengan evaluasi jumlah tembakan (*qty shoot*) serta analisis perkembangan skor bulanan yang terukur.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                    <div class="p-3 rounded-xl bg-[#FAFAFB] border border-[#E5E7EB]">
                        <div class="text-xs font-bold text-[#1F2937]">Pembinaan Teknik</div>
                        <div class="text-[11px] text-[#6B7280] mt-0.5">Postur, anchor, & release konsisten</div>
                    </div>
                    <div class="p-3 rounded-xl bg-[#FAFAFB] border border-[#E5E7EB]">
                        <div class="text-xs font-bold text-[#1F2937]">Evaluasi Terukur</div>
                        <div class="text-[11px] text-[#6B7280] mt-0.5">Monitoring skor & volume panah</div>
                    </div>
                    <div class="p-3 rounded-xl bg-[#FAFAFB] border border-[#E5E7EB]">
                        <div class="text-xs font-bold text-[#1F2937]">Persiapan Lomba</div>
                        <div class="text-[11px] text-[#6B7280] mt-0.5">Pendampingan turnamen resmi</div>
                    </div>
                </div>

                <div class="pt-1">
                    <a href="{{ route('profil') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-700">
                        <span>Lihat Profil Lengkap Klub</span>
                        <span>→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =====================================================================
     5. PILIH CARA GABUNG (3 Kartu Harga, Kartu Tengah Ditonjolkan)
     ===================================================================== --}}
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="text-center max-w-xl mx-auto mb-10 reveal-item">
        <span class="text-xs font-bold uppercase tracking-wider text-primary-600 bg-primary-50 px-3 py-1 rounded-full">Program & Keanggotaan</span>
        <h2 class="text-2xl sm:text-3xl font-bold text-[#1F2937] mt-3 mb-2">Pilih Cara Gabung</h2>
        <p class="text-sm text-[#6B7280]">Mulai dari sesi perkenalan hingga menjadi anggota resmi komunitas.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch reveal-item">
        {{-- Kartu 1: Trial --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-7 shadow-sm flex flex-col justify-between">
            <div>
                <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center text-xl mb-4">🎯</div>
                <h3 class="text-lg font-bold text-[#1F2937]">Trial</h3>
                <p class="text-sm text-[#6B7280] mt-1.5 leading-relaxed">
                    Cocok untuk pemula yang ingin mencoba sensasi memanah pertama kali didampingi pelatih.
                </p>
                <div class="my-6 pt-5 border-t border-[#E5E7EB]">
                    <span class="text-3xl font-extrabold text-[#1F2937]">Rp75.000</span>
                    <span class="text-xs text-[#6B7280] block mt-1">per pertemuan</span>
                </div>
                <ul class="space-y-2 text-xs text-[#6B7280] mb-6">
                    <li>✓ Dipinjami busur & anak panah standar</li>
                    <li>✓ Pengenalan teknik dasar & keselamatan</li>
                    <li>✓ Pendampingan langsung di lapangan</li>
                </ul>
            </div>
            <a href="{{ route('daftar', ['pilihan' => 'trial']) }}"
               class="block w-full py-2.5 px-4 bg-primary-50 hover:bg-primary-100 text-primary-700 font-semibold text-sm text-center rounded-xl transition-colors">
                Pilih Trial
            </a>
        </div>

        {{-- Kartu 2 (Tengah Ditonjolkan): Private Class --}}
        <div class="bg-white rounded-2xl border-2 border-primary-600 ring-4 ring-primary-50 p-7 shadow-md relative flex flex-col justify-between md:-translate-y-1">
            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-primary-600 text-white text-xs font-semibold px-3.5 py-1 rounded-full shadow-sm">
                Paling Populer
            </div>
            <div>
                <div class="w-11 h-11 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center text-xl mb-4">🏹</div>
                <h3 class="text-lg font-bold text-[#1F2937]">Private Class</h3>
                <p class="text-sm text-[#6B7280] mt-1.5 leading-relaxed">
                    Program intensif 6 kali pertemuan bersama pelatih untuk menguasai teknik memanah dari nol.
                </p>
                <div class="my-6 pt-5 border-t border-[#E5E7EB]">
                    <span class="text-3xl font-extrabold text-primary-600">Rp500.000</span>
                    <span class="text-xs text-[#6B7280] block mt-1">paket 6x pertemuan</span>
                </div>
                <ul class="space-y-2 text-xs text-[#6B7280] mb-6">
                    <li>✓ Kurikulum teknik dasar s.d. jarak menengah</li>
                    <li>✓ Jadwal latihan lebih terfokus</li>
                    <li>✓ Fasilitas alat lengkap selama program</li>
                </ul>
            </div>
            <a href="{{ route('daftar', ['pilihan' => 'private']) }}"
               class="block w-full py-2.5 px-4 bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm text-center rounded-xl transition-colors shadow-sm">
                Daftar Private Class
            </a>
        </div>

        {{-- Kartu 3: Join Komunitas --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-7 shadow-sm flex flex-col justify-between">
            <div>
                <div class="w-11 h-11 rounded-xl bg-accent-100 text-primary-700 flex items-center justify-center text-xl mb-4">🌟</div>
                <h3 class="text-lg font-bold text-[#1F2937]">Join Komunitas</h3>
                <p class="text-sm text-[#6B7280] mt-1.5 leading-relaxed">
                    Bergabung resmi menjadi anggota reguler SAC dan dapatkan akses penuh ke portal progres & leaderboard.
                </p>
                <div class="my-6 pt-5 border-t border-[#E5E7EB]">
                    <span class="text-3xl font-extrabold text-[#1F2937]">Rp350.000</span>
                    <span class="text-xs text-[#6B7280] block mt-1">biaya pendaftaran anggota</span>
                </div>
                <ul class="space-y-2 text-xs text-[#6B7280] mb-6">
                    <li>✓ Latihan rutin mingguan bersama klub</li>
                    <li>✓ Akun portal skor, grafik progres, & KTA</li>
                    <li>✓ Kesempatan mewakili klub di kejuaraan</li>
                </ul>
            </div>
            <a href="{{ route('daftar', ['pilihan' => 'reguler']) }}"
               class="block w-full py-2.5 px-4 bg-primary-50 hover:bg-primary-100 text-primary-700 font-semibold text-sm text-center rounded-xl transition-colors">
                Join Komunitas
            </a>
        </div>
    </div>
</section>

{{-- =====================================================================
     6. JADWAL LATIHAN MINGGUAN (#jadwal) — Filter Wajib / Mandiri
     ===================================================================== --}}
<section id="jadwal" data-nav-section="jadwal" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14" x-data="{ filterJadwal: 'semua' }">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8 reveal-item">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-primary-600 bg-primary-50 px-3 py-1 rounded-full">Agenda Klub</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#1F2937] mt-3">Jadwal Latihan Mingguan</h2>
            <p class="text-sm text-[#6B7280] mt-1">Jadwal latihan terbaru dari database Sunnah Archery Community.</p>
        </div>

        {{-- Filter Wajib / Mandiri --}}
        <div class="inline-flex bg-white border border-[#E5E7EB] rounded-xl p-1 gap-1 self-start sm:self-auto">
            <button type="button" @click="filterJadwal = 'semua'"
                    :class="filterJadwal === 'semua' ? 'bg-primary-600 text-white shadow-sm' : 'text-[#6B7280] hover:text-[#1F2937]'"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition">
                Semua
            </button>
            <button type="button" @click="filterJadwal = 'wajib'"
                    :class="filterJadwal === 'wajib' ? 'bg-primary-600 text-white shadow-sm' : 'text-[#6B7280] hover:text-[#1F2937]'"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition">
                Wajib
            </button>
            <button type="button" @click="filterJadwal = 'mandiri'"
                    :class="filterJadwal === 'mandiri' ? 'bg-primary-600 text-white shadow-sm' : 'text-[#6B7280] hover:text-[#1F2937]'"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition">
                Mandiri
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 reveal-item">
        @forelse ($jadwalList ?? [] as $j)
            <div x-show="filterJadwal === 'semua' || filterJadwal === '{{ $j->jenis }}'"
                 x-transition
                 class="bg-white rounded-2xl border border-[#E5E7EB] p-5 shadow-sm flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $j->jenis === 'wajib' ? 'bg-primary-50 text-primary-700' : 'bg-sky-50 text-sky-700' }}">
                            Latihan {{ ucfirst($j->jenis) }}
                        </span>
                        <span class="text-xs font-mono text-[#6B7280]">
                            {{ substr($j->waktu_mulai, 0, 5) }} - {{ substr($j->waktu_selesai, 0, 5) }} WIB
                        </span>
                    </div>
                    <h3 class="font-bold text-base text-[#1F2937]">
                        {{ $j->tanggal->translatedFormat('l, d F Y') }}
                    </h3>
                    <p class="text-xs text-[#6B7280] mt-1.5">
                        📍 {{ $j->lokasi }}
                    </p>
                </div>
                @if ($j->keterangan)
                    <div class="pt-3 border-t border-[#E5E7EB] text-xs text-[#6B7280]">
                        {{ $j->keterangan }}
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-[#E5E7EB] p-8 text-center text-sm text-[#6B7280]">
                Jadwal latihan rutin dilaksanakan setiap hari Ahad pukul 07.00 – 09.00 WIB di Lapangan Archery SAC, Batam.
            </div>
        @endforelse
    </div>
</section>

{{-- =====================================================================
     7. KEJUARAAN TERBARU (#kejuaraan) — Filter Lokal / Provinsi / Nasional
     ===================================================================== --}}
<section id="kejuaraan" data-nav-section="kejuaraan" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14" x-data="{ filterTingkat: 'semua' }">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8 reveal-item">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-primary-600 bg-primary-50 px-3 py-1 rounded-full">Rekam Jejak & Medali</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#1F2937] mt-3">Kejuaraan & Prestasi Anggota</h2>
            <p class="text-sm text-[#6B7280] mt-1">Daftar turnamen yang diikuti atlet Sunnah Archery Community.</p>
        </div>

        {{-- Filter Tingkat --}}
        <div class="inline-flex flex-wrap bg-white border border-[#E5E7EB] rounded-xl p-1 gap-1 self-start sm:self-auto">
            @foreach ([
                ['semua', 'Semua'],
                ['lokal', 'Lokal'],
                ['provinsi', 'Provinsi'],
                ['nasional', 'Nasional'],
            ] as [$val, $lbl])
                <button type="button" @click="filterTingkat = '{{ $val }}'"
                        :class="filterTingkat === '{{ $val }}' ? 'bg-primary-600 text-white shadow-sm' : 'text-[#6B7280] hover:text-[#1F2937]'"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition">
                    {{ $lbl }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 reveal-item">
        @forelse ($kejuaraanList ?? [] as $k)
            <div x-show="filterTingkat === 'semua' || filterTingkat === '{{ $k->tingkat }}'"
                 x-transition
                 class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden flex flex-col justify-between">
                <div class="p-6 border-b border-[#E5E7EB] flex items-start justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-primary-50 text-primary-700">
                                {{ $k->tingkat }}
                            </span>
                            <span class="text-xs text-[#6B7280]">{{ $k->tanggal->translatedFormat('d M Y') }}</span>
                        </div>
                        <h3 class="text-base font-bold text-[#1F2937]">{{ $k->nama_kejuaraan }}</h3>
                        <p class="text-xs text-[#6B7280] mt-0.5">📍 {{ $k->lokasi }}</p>
                    </div>
                    <span class="text-2xl">🏆</span>
                </div>

                <div class="p-5 bg-[#FAFAFB] flex-1">
                    <div class="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-2.5">Prestasi Atlet SAC</div>
                    <div class="space-y-2">
                        @forelse ($k->hasilLomba->take(4) as $hl)
                            <div class="flex items-center justify-between text-xs bg-white px-3 py-2 rounded-xl border border-[#E5E7EB]">
                                <div>
                                    <span class="font-bold text-[#1F2937]">{{ $hl->anggota?->nama ?? 'Atlet SAC' }}</span>
                                    <span class="text-[#6B7280]">· {{ $hl->kategori }} ({{ $hl->jarak_meter }}m)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-[#1F2937]">#{{ $hl->peringkat }}</span>
                                    @if ($hl->medali)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ strtolower($hl->medali) === 'emas' ? 'bg-amber-50 text-amber-700' : (strtolower($hl->medali) === 'perak' ? 'bg-gray-100 text-gray-700' : 'bg-rose-50 text-rose-700') }}">
                                            {{ $hl->medali }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-[#6B7280] italic">Partisipasi tim SAC.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-[#E5E7EB] p-8 text-center text-sm text-[#6B7280]">
                Belum ada data kejuaraan yang ditampilkan.
            </div>
        @endforelse
    </div>
</section>

{{-- =====================================================================
     8. DOKUMENTASI (#dokumentasi) — Grid Foto Galeri + Tombol Lihat Semua
     ===================================================================== --}}
<section id="dokumentasi" data-nav-section="dokumentasi" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="flex items-end justify-between mb-8 reveal-item">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-primary-600 bg-primary-50 px-3 py-1 rounded-full">Galeri Klub</span>
            <h2 class="text-2xl sm:text-3xl font-bold text-[#1F2937] mt-3">Dokumentasi Kegiatan</h2>
            <p class="text-sm text-[#6B7280] mt-1">Suasana latihan rutin dan kebersamaan anggota di lapangan.</p>
        </div>
        <a href="{{ route('dokumentasi') }}"
           class="bg-primary-50 hover:bg-primary-100 text-primary-700 font-semibold text-xs px-4 py-2.5 rounded-xl transition-colors">
            Lihat Semua →
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 reveal-item">
        @forelse ($galeri as $foto)
            @php
                $exists = $foto->file && file_exists(storage_path('app/public/' . $foto->file));
            @endphp
            <div class="group bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden hover:border-primary-300 transition-all">
                @if ($exists)
                    <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                        <img src="{{ asset('storage/' . $foto->file) }}" alt="{{ $foto->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                @else
                    <div class="aspect-[4/3] bg-gray-100 flex flex-col items-center justify-center p-4">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-[#E5E7EB] flex items-center justify-center text-2xl mb-2">🏹</div>
                        <span class="text-[11px] text-[#6B7280] font-medium">Dokumentasi SAC</span>
                    </div>
                @endif
                <div class="p-3.5 flex items-center justify-between gap-2">
                    <span class="text-xs font-semibold text-[#1F2937] truncate">{{ $foto->judul }}</span>
                    <span class="text-[11px] text-[#6B7280] flex-shrink-0">{{ $foto->tanggal->translatedFormat('d M Y') }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-[#E5E7EB] p-8 text-center text-sm text-[#6B7280]">
                Belum ada foto dokumentasi.
            </div>
        @endforelse
    </div>
</section>

{{-- =====================================================================
     9. FAQ ACCORDION
     ===================================================================== --}}
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-14" x-data="{ activeFaq: 0 }">
    <div class="text-center mb-8 reveal-item">
        <span class="text-xs font-bold uppercase tracking-wider text-primary-600 bg-primary-50 px-3 py-1 rounded-full">Tanya Jawab</span>
        <h2 class="text-2xl sm:text-3xl font-bold text-[#1F2937] mt-3">Pertanyaan yang Sering Diajukan</h2>
        <p class="text-sm text-[#6B7280] mt-1">Informasi penting seputar latihan dan pendaftaran di SAC Batam.</p>
    </div>

    @php
        $faqs = [
            [
                'q' => 'Apa saja syarat bergabung di Sunnah Archery Community?',
                'a' => 'Syarat utamanya sangat mudah: wajib follow akun Instagram resmi @sunnah_archery_community dan mengikuti sesi Trial terlebih dahulu untuk pengenalan teknik dasar serta aturan keselamatan di lapangan.',
            ],
            [
                'q' => 'Apakah pemula harus memiliki busur dan anak panah sendiri?',
                'a' => 'Tidak perlu. Untuk peserta Trial maupun Private Class yang belum memiliki busur pribadi, peralatan panahan standar sudah disediakan oleh komunitas selama sesi latihan berlangsung.',
            ],
            [
                'q' => 'Di mana lokasi latihan dan kapan jadwal rutinnya?',
                'a' => 'Latihan berlokasi di Lapangan Archery SAC, Kota Batam. Latihan wajib komunitas dilaksanakan setiap hari Minggu (Ahad) pagi pukul 07.00 – 09.00 WIB, sedangkan latihan mandiri dapat disesuaikan.',
            ],
            [
                'q' => 'Bagaimana cara mendaftar untuk ikut latihan?',
                'a' => 'Klik tombol "Daftar" atau "Coba Trial" di halaman ini, isi formulir pendaftaran singkat, lalu klik tombol konfirmasi ke WhatsApp Admin agar jadwal sesi latihan pertamamu segera disiapkan.',
            ],
        ];
    @endphp

    <div class="space-y-3 reveal-item">
        @foreach ($faqs as $idx => $item)
            <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                <button type="button"
                        @click="activeFaq = (activeFaq === {{ $idx }} ? null : {{ $idx }})"
                        class="w-full px-6 py-4 text-left flex items-center justify-between gap-4 hover:bg-primary-50/40 transition-colors">
                    <span class="font-bold text-sm text-[#1F2937]">{{ $item['q'] }}</span>
                    <span class="w-6 h-6 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center text-xs font-bold flex-shrink-0"
                          x-text="activeFaq === {{ $idx }} ? '−' : '+'">+</span>
                </button>
                <div x-show="activeFaq === {{ $idx }}" x-transition class="px-6 pb-4 text-sm text-[#6B7280] leading-relaxed border-t border-[#E5E7EB] pt-3">
                    {{ $item['a'] }}
                </div>
            </div>
        @endforeach
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.count-up');
    const animateCount = (el) => {
        const target = parseInt(el.getAttribute('data-target') || '0', 10);
        if (target <= 0) {
            el.textContent = '0';
            return;
        }
        const duration = 1100;
        const startTime = performance.now();
        const step = (now) => {
            const progress = Math.min((now - startTime) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target);
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = target;
            }
        };
        requestAnimationFrame(step);
    };

    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCount(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });
        counters.forEach(c => obs.observe(c));
    } else {
        counters.forEach(animateCount);
    }
});
</script>
@endpush

@endsection
