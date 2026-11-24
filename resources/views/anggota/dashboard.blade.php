@extends('layouts.anggota')

@section('title', 'Dashboard Anggota')

@section('content')
<div class="space-y-6">
    {{-- Welcome Card (Clean White + Soft Purple Accents) --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 sm:p-7 shadow-sm relative overflow-hidden">
        <div class="pointer-events-none absolute -right-12 -top-12 w-48 h-48 rounded-full bg-primary-100 opacity-60 blur-3xl"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 bg-primary-50 text-primary-700 rounded-full text-xs font-semibold mb-2.5">
                    Anggota {{ ucfirst($anggota->jenis_keanggotaan) }} · SAC Batam 🏹
                </span>
                <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">
                    Assalamu'alaikum, {{ $anggota->nama }}!
                </h1>
                <p class="text-[#6B7280] text-sm mt-1 max-w-xl leading-relaxed">
                    Tetap istiqomah melatih fisik dan fokus memanah sesuai sunnah. Cek performa latihan dan progres skormu bulan ini di bawah.
                </p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('anggota.progres.kta') }}" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 shadow-sm">
                    <span>🪪</span> Unduh Kartu Anggota (KTA)
                </a>
            </div>
        </div>
    </div>

    {{-- Highlight Status Performa --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Status Evaluasi --}}
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Status Performa</div>
            <div class="my-3">
                @php
                    $statusColor = match ($status['status']) {
                        'Naik'                 => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'Tetap'                => 'bg-sky-50 text-sky-700 border-sky-200',
                        'Turun'                => 'bg-rose-50 text-rose-700 border-rose-200',
                        'Jarang Latihan'       => 'bg-amber-50 text-amber-700 border-amber-200',
                        default                => 'bg-gray-50 text-[#6B7280] border-gray-200',
                    };
                @endphp
                <span class="inline-block px-3 py-1.5 rounded-xl border text-sm font-bold {{ $statusColor }}">
                    {{ $status['status'] }}
                </span>
            </div>
            <div class="text-xs text-[#6B7280]">
                Bulan: <strong class="text-[#1F2937]">{{ now()->translatedFormat('F Y') }}</strong>
            </div>
        </div>

        {{-- Rata-Rata Skor --}}
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Rata-rata Skor Bulan Ini</div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-[#1F2937]">
                    {{ $status['rata_ini'] !== null ? number_format($status['rata_ini'], 1) : '-' }}
                </span>
                @if ($status['selisih'] !== null)
                    <span class="text-xs font-bold {{ $status['selisih'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $status['selisih'] >= 0 ? '▲ +' : '▼ ' }}{{ number_format($status['selisih'], 1) }} poin
                    </span>
                @endif
            </div>
            <div class="text-xs text-[#6B7280]">
                Bulan lalu: {{ $status['rata_lalu'] !== null ? number_format($status['rata_lalu'], 1) : 'Belum ada' }}
            </div>
        </div>

        {{-- Sesi Latihan --}}
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Sesi Latihan Valid</div>
            <div class="my-2">
                <span class="text-3xl font-extrabold text-primary-600">
                    {{ $status['jumlah_sesi'] }}
                </span>
                <span class="text-xs text-[#6B7280] font-normal"> / min. 4 sesi</span>
            </div>
            <div class="text-xs text-[#6B7280]">
                {{ $status['jumlah_sesi'] >= 4 ? '✅ Target sesi tercapai' : '⚠️ Kurang ' . (4 - $status['jumlah_sesi']) . ' sesi lagi' }}
            </div>
        </div>
    </div>

    {{-- Rekomendasi Pelatih / Sistem --}}
    @if (!empty($status['saran']))
        <div class="bg-primary-50/70 border border-primary-200 rounded-2xl p-5 flex items-start gap-3.5">
            <span class="text-2xl flex-shrink-0">💡</span>
            <div>
                <h2 class="text-sm font-bold text-primary-900">Catatan & Rekomendasi Latihan</h2>
                <p class="text-xs sm:text-sm text-[#1F2937] mt-1 leading-relaxed">
                    {{ $status['saran'] }}
                </p>
            </div>
        </div>
    @endif

    {{-- Menu Pintas & Pengumuman --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Menu Aksi Cepat --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6 space-y-3">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Aksi Cepat</h2>

            @if (Route::has('anggota.skor'))
                <a href="{{ route('anggota.skor') }}" class="flex items-center justify-between p-3 rounded-xl bg-[#FAFAFB] border border-[#E5E7EB] hover:bg-primary-50 hover:border-primary-200 hover:text-primary-700 transition font-medium text-sm text-[#1F2937] group">
                    <span class="flex items-center gap-2.5">
                        <span class="text-base">🎯</span> Catat Skor Tembakan
                    </span>
                    <span class="text-[#6B7280] group-hover:text-primary-600">→</span>
                </a>
            @endif

            @if (Route::has('anggota.jadwal'))
                <a href="{{ route('anggota.jadwal') }}" class="flex items-center justify-between p-3 rounded-xl bg-[#FAFAFB] border border-[#E5E7EB] hover:bg-primary-50 hover:border-primary-200 hover:text-primary-700 transition font-medium text-sm text-[#1F2937] group">
                    <span class="flex items-center gap-2.5">
                        <span class="text-base">📅</span> Jadwal Latihan & Presensi
                    </span>
                    <span class="text-[#6B7280] group-hover:text-primary-600">→</span>
                </a>
            @endif

            @if (Route::has('anggota.progres'))
                <a href="{{ route('anggota.progres') }}" class="flex items-center justify-between p-3 rounded-xl bg-[#FAFAFB] border border-[#E5E7EB] hover:bg-primary-50 hover:border-primary-200 hover:text-primary-700 transition font-medium text-sm text-[#1F2937] group">
                    <span class="flex items-center gap-2.5">
                        <span class="text-base">📈</span> Grafik Progres Saya
                    </span>
                    <span class="text-[#6B7280] group-hover:text-primary-600">→</span>
                </a>
            @endif

            @if (Route::has('anggota.leaderboard'))
                <a href="{{ route('anggota.leaderboard') }}" class="flex items-center justify-between p-3 rounded-xl bg-[#FAFAFB] border border-[#E5E7EB] hover:bg-primary-50 hover:border-primary-200 hover:text-primary-700 transition font-medium text-sm text-[#1F2937] group">
                    <span class="flex items-center gap-2.5">
                        <span class="text-base">🏆</span> Klasemen Leaderboard
                    </span>
                    <span class="text-[#6B7280] group-hover:text-primary-600">→</span>
                </a>
            @endif
        </div>

        {{-- Pengumuman Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Pengumuman Terbaru</h2>
                @if (Route::has('anggota.pengumuman'))
                    <a href="{{ route('anggota.pengumuman') }}" class="text-xs text-primary-600 hover:text-primary-700 hover:underline font-semibold">
                        Lihat Semua →
                    </a>
                @endif
            </div>

            <div class="space-y-3">
                @forelse ($pengumuman as $info)
                    <div class="p-4 rounded-xl border border-[#E5E7EB] hover:border-primary-200 transition bg-[#FAFAFB]">
                        <div class="flex items-center justify-between text-xs text-[#6B7280] mb-1">
                            <span class="font-semibold text-primary-600">📢 Info SAC</span>
                            <span>{{ \Illuminate\Support\Carbon::parse($info->tanggal)->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="font-bold text-sm text-[#1F2937]">{{ $info->judul }}</div>
                        <p class="text-xs text-[#6B7280] mt-1 line-clamp-2 leading-relaxed">
                            {{ $info->isi }}
                        </p>
                    </div>
                @empty
                    <p class="text-xs text-[#6B7280] text-center py-6">Belum ada pengumuman terbaru.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
