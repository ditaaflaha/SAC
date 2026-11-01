@extends('layouts.anggota')

@section('title', 'Dashboard Anggota')

@section('content')
<div class="space-y-6">
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-semibold">
                    Anggota {{ ucfirst($anggota->jenis_keanggotaan) }} · SAC Batam 🏹
                </span>
                <a href="{{ route('anggota.progres.kta') }}" class="px-3.5 py-1.5 bg-white/20 hover:bg-white/30 backdrop-blur rounded-xl text-xs font-bold text-white transition inline-flex items-center gap-1.5 shadow-sm">
                    <span>🪪</span> Unduh Kartu Anggota (KTA)
                </a>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                Assalamu'alaikum, {{ $anggota->nama }}!
            </h1>
            <p class="text-emerald-100 text-sm mt-1 max-w-xl leading-relaxed">
                Tetap istiqomah melatih fisik dan fokus memanah sesuai sunnah. Cek performa latihan dan progres skormu bulan ini di bawah!
            </p>
        </div>
        <div class="absolute -right-6 -bottom-8 opacity-15 text-9xl pointer-events-none select-none">
            🎯
        </div>
    </div>

    {{-- Highlight Status Performa & Iuran --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Status Evaluasi --}}
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status Performa</div>
            <div class="my-3">
                @php
                    $statusColor = match ($status['status']) {
                        'Naik'                 => 'text-emerald-600 bg-emerald-50 border-emerald-200',
                        'Tetap'                => 'text-blue-600 bg-blue-50 border-blue-200',
                        'Turun'                => 'text-rose-600 bg-rose-50 border-rose-200',
                        'Jarang Latihan'       => 'text-amber-600 bg-amber-50 border-amber-200',
                        default                => 'text-gray-600 bg-gray-50 border-gray-200',
                    };
                @endphp
                <span class="inline-block px-3 py-1.5 rounded-xl border text-base font-extrabold {{ $statusColor }}">
                    {{ $status['status'] }}
                </span>
            </div>
            <div class="text-xs text-gray-500">
                Bulan: <strong>{{ now()->translatedFormat('F Y') }}</strong>
            </div>
        </div>

        {{-- Rata-Rata Skor --}}
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rata-rata Skor Bulan Ini</div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-gray-900 font-mono">
                    {{ $status['rata_ini'] !== null ? number_format($status['rata_ini'], 1) : '-' }}
                </span>
                @if ($status['selisih'] !== null)
                    <span class="text-xs font-bold {{ $status['selisih'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $status['selisih'] >= 0 ? '▲ +' : '▼ ' }}{{ number_format($status['selisih'], 1) }} poin
                    </span>
                @endif
            </div>
            <div class="text-xs text-gray-400">
                Bulan lalu: {{ $status['rata_lalu'] !== null ? number_format($status['rata_lalu'], 1) : 'Belum ada' }}
            </div>
        </div>

        {{-- Sesi Latihan --}}
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sesi Latihan Valid</div>
            <div class="my-2">
                <span class="text-3xl font-black text-gray-900 font-mono">
                    {{ $status['jumlah_sesi'] }}
                </span>
                <span class="text-xs text-gray-500 font-normal"> / min. 4 sesi</span>
            </div>
            <div class="text-xs text-gray-400">
                {{ $status['jumlah_sesi'] >= 4 ? '✅ Target sesi tercapai' : '⚠️ Kurang ' . (4 - $status['jumlah_sesi']) . ' sesi lagi' }}
            </div>
        </div>

        {{-- Status Iuran --}}
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Iuran Bulan Ini</div>
            <div class="my-3">
                @php
                    $iuranBadge = match ($statusIuran) {
                        'Lunas' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'FREE'  => 'bg-purple-100 text-purple-800 border-purple-200',
                        'C1'    => 'bg-amber-100 text-amber-800 border-amber-200',
                        default => 'bg-red-100 text-red-800 border-red-200',
                    };
                @endphp
                <span class="inline-block px-3 py-1.5 rounded-xl border text-sm font-extrabold {{ $iuranBadge }}">
                    {{ $statusIuran === 'C1' ? 'Cicilan 1 Selesai' : $statusIuran }}
                </span>
            </div>
            <div class="text-xs text-gray-500">
                @if (Route::has('anggota.iuran'))
                    <a href="{{ route('anggota.iuran') }}" class="text-emerald-600 hover:underline font-semibold">
                        Lihat Rincian →
                    </a>
                @else
                    Iuran bulanan: Rp40.000
                @endif
            </div>
        </div>
    </div>

    {{-- Rekomendasi Pelatih / Sistem --}}
    @if (!empty($status['saran']))
        <div class="bg-amber-50 border border-amber-200/80 rounded-2xl p-5 flex items-start gap-3.5">
            <span class="text-2xl flex-shrink-0">💡</span>
            <div>
                <h2 class="text-sm font-bold text-amber-900">Catatan & Rekomendasi Latihan</h2>
                <p class="text-xs sm:text-sm text-amber-800 mt-1 leading-relaxed">
                    {{ $status['saran'] }}
                </p>
            </div>
        </div>
    @endif

    {{-- Menu Pintas & Pengumuman --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Menu Aksi Cepat --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-3">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Aksi Cepat</h2>

            @if (Route::has('anggota.skor'))
                <a href="{{ route('anggota.skor') }}" class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-emerald-50 hover:text-emerald-700 transition font-medium text-sm text-gray-700 group">
                    <span class="flex items-center gap-2.5">
                        <span class="text-base">🎯</span> Catat Skor Tembakan
                    </span>
                    <span class="text-gray-400 group-hover:text-emerald-600">→</span>
                </a>
            @endif

            @if (Route::has('anggota.jadwal'))
                <a href="{{ route('anggota.jadwal') }}" class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-emerald-50 hover:text-emerald-700 transition font-medium text-sm text-gray-700 group">
                    <span class="flex items-center gap-2.5">
                        <span class="text-base">📅</span> Jadwal Latihan & Presensi
                    </span>
                    <span class="text-gray-400 group-hover:text-emerald-600">→</span>
                </a>
            @endif

            @if (Route::has('anggota.progres'))
                <a href="{{ route('anggota.progres') }}" class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-emerald-50 hover:text-emerald-700 transition font-medium text-sm text-gray-700 group">
                    <span class="flex items-center gap-2.5">
                        <span class="text-base">📈</span> Grafik Progres Saya
                    </span>
                    <span class="text-gray-400 group-hover:text-emerald-600">→</span>
                </a>
            @endif

            @if (Route::has('anggota.leaderboard'))
                <a href="{{ route('anggota.leaderboard') }}" class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-emerald-50 hover:text-emerald-700 transition font-medium text-sm text-gray-700 group">
                    <span class="flex items-center gap-2.5">
                        <span class="text-base">🏆</span> Klasemen Leaderboard
                    </span>
                    <span class="text-gray-400 group-hover:text-emerald-600">→</span>
                </a>
            @endif
        </div>

        {{-- Pengumuman Terbaru --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Pengumuman Terbaru</h2>
                @if (Route::has('anggota.pengumuman'))
                    <a href="{{ route('anggota.pengumuman') }}" class="text-xs text-emerald-600 hover:underline font-semibold">
                        Lihat Semua →
                    </a>
                @endif
            </div>

            <div class="space-y-3">
                @forelse ($pengumuman as $info)
                    <div class="p-4 rounded-xl border border-gray-100 hover:border-emerald-200 transition bg-gray-50/50">
                        <div class="flex items-center justify-between text-xs text-gray-400 mb-1">
                            <span class="font-semibold text-emerald-600">📢 Info SAC</span>
                            <span>{{ \Illuminate\Support\Carbon::parse($info->tanggal)->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="font-bold text-sm text-gray-800">{{ $info->judul }}</div>
                        <p class="text-xs text-gray-600 mt-1 line-clamp-2 leading-relaxed">
                            {{ $info->isi }}
                        </p>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center py-6">Belum ada pengumuman terbaru.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
