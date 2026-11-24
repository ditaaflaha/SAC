@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    {{-- Header Dashboard --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Dashboard Ringkasan</h1>
            <p class="text-sm text-[#6B7280] mt-0.5">
                Periode: <strong class="text-[#1F2937]">{{ $bulanIni->translatedFormat('F Y') }}</strong>
            </p>
        </div>
        <div class="flex items-center gap-2">
            @if (Route::has('admin.validasi-skor'))
                <a href="{{ route('admin.validasi-skor') }}" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition shadow-sm inline-flex items-center gap-1.5">
                    <span>🎯</span> Validasi Skor
                </a>
            @endif
            @if (Route::has('admin.keanggotaan'))
                <a href="{{ route('admin.keanggotaan') }}" class="px-4 py-2 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5">
                    <span>👥</span> Kelola Anggota
                </a>
            @endif
        </div>
    </div>

    {{-- Kartu Statistik Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Total Anggota Aktif --}}
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-sm">
            <div class="flex items-center justify-between text-[#6B7280] mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Anggota Aktif</span>
                <span class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center text-sm">🏹</span>
            </div>
            <div class="text-3xl font-extrabold text-[#1F2937]">{{ $totalAnggotaAktif }}</div>
            <p class="text-xs text-[#6B7280] mt-1">Personil aktif klub</p>
        </div>

        {{-- Perlu Perhatian --}}
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-sm">
            <div class="flex items-center justify-between text-[#6B7280] mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Perlu Perhatian</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm">⚠️</span>
            </div>
            <div class="text-3xl font-extrabold text-amber-600">{{ $perluPerhatian }}</div>
            <p class="text-xs text-[#6B7280] mt-1">Tetap / Turun / Jarang</p>
        </div>

        {{-- Pendaftar Baru --}}
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-sm">
            <div class="flex items-center justify-between text-[#6B7280] mb-2">
                <span class="text-xs font-semibold uppercase tracking-wider">Pendaftar Baru</span>
                <span class="w-8 h-8 rounded-xl bg-accent-100 text-primary-700 flex items-center justify-center text-sm">✨</span>
            </div>
            <div class="text-3xl font-extrabold text-primary-600">{{ $pendaftarBaru }}</div>
            <p class="text-xs text-[#6B7280] mt-1">Menunggu approval</p>
        </div>
    </div>

    {{-- Ringkasan Status Komunitas (Badge Pastel) --}}
    @php
        $statusCounts = $rekapKomunitas->groupBy('status')->map->count();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="bg-emerald-50 border border-emerald-200/70 p-3.5 rounded-2xl">
            <div class="text-xs font-bold text-emerald-700">Status Naik</div>
            <div class="text-2xl font-extrabold text-emerald-700 mt-0.5">{{ $statusCounts->get('Naik', 0) }}</div>
            <div class="text-[11px] text-emerald-600">Peningkatan ≥ 5 poin</div>
        </div>
        <div class="bg-sky-50 border border-sky-200/70 p-3.5 rounded-2xl">
            <div class="text-xs font-bold text-sky-700">Status Tetap</div>
            <div class="text-2xl font-extrabold text-sky-700 mt-0.5">{{ $statusCounts->get('Tetap', 0) }}</div>
            <div class="text-[11px] text-sky-600">Fluktuasi &lt; 5 poin</div>
        </div>
        <div class="bg-rose-50 border border-rose-200/70 p-3.5 rounded-2xl">
            <div class="text-xs font-bold text-rose-700">Status Turun</div>
            <div class="text-2xl font-extrabold text-rose-700 mt-0.5">{{ $statusCounts->get('Turun', 0) }}</div>
            <div class="text-[11px] text-rose-600">Penurunan ≥ 5 poin</div>
        </div>
        <div class="bg-amber-50 border border-amber-200/70 p-3.5 rounded-2xl">
            <div class="text-xs font-bold text-amber-700">Jarang Latihan</div>
            <div class="text-2xl font-extrabold text-amber-700 mt-0.5">{{ $statusCounts->get('Jarang Latihan', 0) }}</div>
            <div class="text-[11px] text-amber-600">Kurang dari min. sesi</div>
        </div>
        <div class="bg-primary-50 border border-primary-200/70 p-3.5 rounded-2xl">
            <div class="text-xs font-bold text-primary-700">Baru / Pembanding</div>
            <div class="text-2xl font-extrabold text-primary-700 mt-0.5">{{ $statusCounts->get('Belum Ada Pembanding', 0) }}</div>
            <div class="text-[11px] text-primary-600">Data bulan lalu kosong</div>
        </div>
    </div>

    {{-- Tabel Analisis Anggota Komunitas --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-[#E5E7EB] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-[#1F2937]">Analisis Perkembangan Anggota</h2>
                <p class="text-xs text-[#6B7280]">Hasil evaluasi rata-rata skor tembakan bulan ini vs bulan lalu</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 bg-primary-50 text-primary-700 rounded-full">
                {{ $rekapKomunitas->count() }} Anggota Dievaluasi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[#6B7280]">
                <thead class="bg-[#FAFAFB] text-xs uppercase font-semibold text-[#6B7280] border-b border-[#E5E7EB]">
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
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse ($rekapKomunitas as $item)
                        <tr class="hover:bg-primary-50/30 transition">
                            <td class="px-6 py-3.5">
                                <div class="font-bold text-[#1F2937]">{{ $item['anggota']->nama }}</div>
                                <div class="text-xs text-[#6B7280]">
                                    {{ $item['anggota']->jenis_keanggotaan }} · {{ $item['anggota']->jenis_kelamin === 'L' ? 'Ikhwan' : 'Akhwat' }}
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-center font-semibold text-[#1F2937]">
                                {{ $item['jumlah_sesi'] }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-mono text-[#6B7280]">
                                {{ $item['rata_lalu'] !== null ? number_format($item['rata_lalu'], 1) : '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-mono font-bold text-[#1F2937]">
                                {{ $item['rata_ini'] !== null ? number_format($item['rata_ini'], 1) : '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-center font-mono font-bold">
                                @if ($item['selisih'] !== null)
                                    <span class="{{ $item['selisih'] > 0 ? 'text-emerald-600' : ($item['selisih'] < 0 ? 'text-rose-600' : 'text-[#6B7280]') }}">
                                        {{ $item['selisih'] > 0 ? '+' : '' }}{{ number_format($item['selisih'], 1) }}
                                    </span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @php
                                    $badge = match ($item['status']) {
                                        'Naik'                 => 'bg-emerald-50 text-emerald-700',
                                        'Tetap'                => 'bg-sky-50 text-sky-700',
                                        'Turun'                => 'bg-rose-50 text-rose-700',
                                        'Jarang Latihan'       => 'bg-amber-50 text-amber-700',
                                        default                => 'bg-gray-100 text-[#6B7280]',
                                    };
                                @endphp
                                <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full {{ $badge }}">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-[#6B7280] max-w-xs truncate" title="{{ $item['saran'] }}">
                                {{ $item['saran'] ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-[#6B7280]">
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
