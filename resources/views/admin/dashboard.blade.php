@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    {{-- Header Dashboard --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Ringkasan</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                Periode: <strong class="text-slate-700">{{ $bulanIni->translatedFormat('F Y') }}</strong>
            </p>
        </div>
        <div class="flex items-center gap-2">
            @if (Route::has('admin.validasi-skor'))
                <a href="{{ route('admin.validasi-skor') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm inline-flex items-center gap-1.5">
                    <span>🎯</span> Validasi Skor
                </a>
            @endif
            @if (Route::has('admin.keanggotaan'))
                <a href="{{ route('admin.keanggotaan') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition shadow-sm inline-flex items-center gap-1.5">
                    <span>👥</span> Kelola Anggota
                </a>
            @endif
        </div>
    </div>

    {{-- Kartu Statistik Ringkasan --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Total Anggota Aktif --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Anggota Aktif</span>
                <span class="text-lg">🏹</span>
            </div>
            <div class="text-2xl font-black text-slate-800">{{ $totalAnggotaAktif }}</div>
            <p class="text-xs text-slate-400 mt-1">Personil aktif klub</p>
        </div>

        {{-- Perlu Perhatian --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Perlu Perhatian</span>
                <span class="text-lg">⚠️</span>
            </div>
            <div class="text-2xl font-black text-amber-600">{{ $perluPerhatian }}</div>
            <p class="text-xs text-amber-600/80 mt-1">Tetap / Turun / Jarang</p>
        </div>

        {{-- Pendaftar Baru --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Pendaftar Baru</span>
                <span class="text-lg">✨</span>
            </div>
            <div class="text-2xl font-black text-indigo-600">{{ $pendaftarBaru }}</div>
            <p class="text-xs text-indigo-500 mt-1">Menunggu approval</p>
        </div>

        {{-- Kas Bulan Ini --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Kas Bulan Ini</span>
                <span class="text-lg">💰</span>
            </div>
            <div class="text-2xl font-black text-emerald-600">Rp{{ number_format($kasbulanIni, 0, ',', '.') }}</div>
            <p class="text-xs text-emerald-600/80 mt-1">Total iuran masuk</p>
        </div>

        {{-- Belum Bayar Iuran --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Belum Bayar</span>
                <span class="text-lg">⏳</span>
            </div>
            <div class="text-2xl font-black text-red-600">{{ $belumBayar }}</div>
            <p class="text-xs text-red-500 mt-1">Anggota reguler</p>
        </div>
    </div>

    {{-- Ringkasan Status Komunitas --}}
    @php
        $statusCounts = $rekapKomunitas->groupBy('status')->map->count();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="bg-emerald-50 border border-emerald-200/60 p-3.5 rounded-2xl">
            <div class="text-xs font-bold text-emerald-800">Status Naik</div>
            <div class="text-xl font-black text-emerald-700 mt-0.5">{{ $statusCounts->get('Naik', 0) }}</div>
            <div class="text-[11px] text-emerald-600">Peningkatan ≥ 5 poin</div>
        </div>
        <div class="bg-blue-50 border border-blue-200/60 p-3.5 rounded-2xl">
            <div class="text-xs font-bold text-blue-800">Status Tetap</div>
            <div class="text-xl font-black text-blue-700 mt-0.5">{{ $statusCounts->get('Tetap', 0) }}</div>
            <div class="text-[11px] text-blue-600">Fluktuasi < 5 poin</div>
        </div>
        <div class="bg-rose-50 border border-rose-200/60 p-3.5 rounded-2xl">
            <div class="text-xs font-bold text-rose-800">Status Turun</div>
            <div class="text-xl font-black text-rose-700 mt-0.5">{{ $statusCounts->get('Turun', 0) }}</div>
            <div class="text-[11px] text-rose-600">Penurunan ≥ 5 poin</div>
        </div>
        <div class="bg-amber-50 border border-amber-200/60 p-3.5 rounded-2xl">
            <div class="text-xs font-bold text-amber-800">Jarang Latihan</div>
            <div class="text-xl font-black text-amber-700 mt-0.5">{{ $statusCounts->get('Jarang Latihan', 0) }}</div>
            <div class="text-[11px] text-amber-600">Kurang dari min. sesi</div>
        </div>
        <div class="bg-slate-100 border border-slate-200 p-3.5 rounded-2xl">
            <div class="text-xs font-bold text-slate-800">Baru / Pembanding</div>
            <div class="text-xl font-black text-slate-700 mt-0.5">{{ $statusCounts->get('Belum Ada Pembanding', 0) }}</div>
            <div class="text-[11px] text-slate-500">Data bulan lalu kosong</div>
        </div>
    </div>

    {{-- Tabel Analisis Anggota Komunitas --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-slate-800">Analisis Perkembangan Anggota</h2>
                <p class="text-xs text-slate-400">Hasil evaluasi rata-rata skor tembakan bulan ini vs bulan lalu</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 bg-slate-100 text-slate-600 rounded-full">
                {{ $rekapKomunitas->count() }} Anggota Dievaluasi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/75 text-xs uppercase font-semibold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Nama Anggota</th>
                        <th class="px-4 py-3 text-center">Sesi</th>
                        <th class="px-4 py-3 text-right">Rata-rata Lalu</th>
                        <th class="px-4 py-3 text-right">Rata-rata Ini</th>
                        <th class="px-4 py-3 text-center">Selisih</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-6 py-3">Rekomendasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($rekapKomunitas as $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-3.5">
                                <div class="font-bold text-slate-800">{{ $item['anggota']->nama }}</div>
                                <div class="text-xs text-slate-400">
                                    {{ $item['anggota']->jenis_keanggotaan }} · {{ $item['anggota']->jenis_kelamin === 'L' ? 'Ikhwan' : 'Akhwat' }}
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-center font-semibold">
                                {{ $item['jumlah_sesi'] }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-mono text-slate-500">
                                {{ $item['rata_lalu'] !== null ? number_format($item['rata_lalu'], 1) : '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-mono font-bold text-slate-800">
                                {{ $item['rata_ini'] !== null ? number_format($item['rata_ini'], 1) : '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-center font-mono font-bold">
                                @if ($item['selisih'] !== null)
                                    <span class="{{ $item['selisih'] > 0 ? 'text-emerald-600' : ($item['selisih'] < 0 ? 'text-rose-600' : 'text-slate-500') }}">
                                        {{ $item['selisih'] > 0 ? '+' : '' }}{{ number_format($item['selisih'], 1) }}
                                    </span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @php
                                    $badge = match ($item['status']) {
                                        'Naik'                 => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'Tetap'                => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'Turun'                => 'bg-rose-100 text-rose-800 border-rose-200',
                                        'Jarang Latihan'       => 'bg-amber-100 text-amber-800 border-amber-200',
                                        default                => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <span class="inline-block px-2.5 py-1 text-xs font-extrabold rounded-full border {{ $badge }}">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-slate-500 max-w-xs truncate" title="{{ $item['saran'] }}">
                                {{ $item['saran'] ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                Belum ada data anggota aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
