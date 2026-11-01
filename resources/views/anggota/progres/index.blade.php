@extends('layouts.anggota')

@section('title', 'Progres Perkembangan Saya')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Progres Perkembangan Latihan</h1>
        <p class="text-sm text-gray-500">Evaluasi perkembangan skor tembakan dan konsistensi latihan memanahmu.</p>
    </div>

    {{-- Highlight Perbandingan Bulan Ini vs Bulan Lalu --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Status Evaluasi --}}
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status Performa</div>
            <div class="my-3">
                @php
                    $color = match ($statusIni['status']) {
                        'Naik'                 => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'Tetap'                => 'bg-blue-100 text-blue-800 border-blue-200',
                        'Turun'                => 'bg-rose-100 text-rose-800 border-rose-200',
                        'Jarang Latihan'       => 'bg-amber-100 text-amber-800 border-amber-200',
                        default                => 'bg-gray-100 text-gray-700 border-gray-200',
                    };
                @endphp
                <span class="inline-block px-3 py-1.5 rounded-xl border text-sm font-extrabold {{ $color }}">
                    {{ $statusIni['status'] }}
                </span>
            </div>
            <div class="text-xs text-gray-500">
                Periode: <strong>{{ $bulanIni->translatedFormat('F Y') }}</strong>
            </div>
        </div>

        {{-- Skor Rata-rata --}}
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rata-rata Skor Bulan Ini</div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-black text-gray-900 font-mono">
                    {{ $statusIni['rata_ini'] !== null ? number_format($statusIni['rata_ini'], 1) : '-' }}
                </span>
                @if ($statusIni['selisih'] !== null)
                    <span class="text-xs font-bold {{ $statusIni['selisih'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $statusIni['selisih'] >= 0 ? '+' : '' }}{{ number_format($statusIni['selisih'], 1) }} poin
                    </span>
                @endif
            </div>
            <div class="text-xs text-gray-400">
                Bulan lalu: {{ $statusIni['rata_lalu'] !== null ? number_format($statusIni['rata_lalu'], 1) : '-' }}
            </div>
        </div>

        {{-- Sesi Valid --}}
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sesi Latihan Valid</div>
            <div class="my-2">
                <span class="text-3xl font-black text-gray-900 font-mono">
                    {{ $statusIni['jumlah_sesi'] }}
                </span>
                <span class="text-xs text-gray-500 font-normal"> / target min. 4</span>
            </div>
            <div class="text-xs text-gray-400">
                Total semua sesi valid: {{ $totalSesiValid }}
            </div>
        </div>

        {{-- Volume Anak Panah --}}
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Volume Panah</div>
            <div class="my-2">
                <span class="text-3xl font-black text-indigo-700 font-mono">
                    {{ $totalAnakPanahBulanIni }}
                </span>
                <span class="text-xs text-gray-500 font-normal"> panah</span>
            </div>
            <div class="text-xs text-gray-400">
                Tercatat pada bulan {{ $bulanIni->translatedFormat('F') }}
            </div>
        </div>
    </div>

    {{-- Rekomendasi Pelatih --}}
    @if (!empty($statusIni['saran']))
        <div class="bg-amber-50 border border-amber-200/80 rounded-2xl p-5 flex items-start gap-3.5">
            <span class="text-2xl flex-shrink-0">💡</span>
            <div>
                <h2 class="text-sm font-bold text-amber-900">Rekomendasi Latihan Kamu</h2>
                <p class="text-xs sm:text-sm text-amber-800 mt-1 leading-relaxed">
                    {{ $statusIni['saran'] }}
                </p>
            </div>
        </div>
    @endif

    {{-- Log Sesi Tembakan Valid --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
                Log Skor Sesi Tembakan Valid
            </h2>
            <span class="text-xs text-gray-400 font-semibold">{{ $riwayatValid->count() }} Sesi Valid</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-400 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5">Tanggal</th>
                        <th class="px-4 py-3.5">Sesi</th>
                        <th class="px-4 py-3.5">Jarak Target</th>
                        <th class="px-4 py-3.5">Qty Panah</th>
                        <th class="px-4 py-3.5 text-right">Total Skor</th>
                        <th class="px-6 py-3.5">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($riwayatValid as $skor)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-900 text-xs">
                                {{ $skor->tanggal->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 py-4 text-xs font-semibold text-gray-700">
                                Sesi ke-{{ $skor->sesi_ke }}
                            </td>
                            <td class="px-4 py-4 text-xs font-bold text-gray-800">
                                {{ $skor->jarak_meter }}m
                            </td>
                            <td class="px-4 py-4 text-xs font-mono font-semibold text-gray-600">
                                {{ $skor->qty_panah }} panah
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-black text-emerald-700 text-base">
                                {{ $skor->total_skor }} poin
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">
                                {{ $skor->catatan ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-xs">
                                Belum ada sesi tembakan valid. Mulai input skor latihanmu sekarang!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

