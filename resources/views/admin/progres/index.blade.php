@extends('layouts.admin')

@section('title', 'Progres & Riwayat Latihan Anggota')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Progres Perkembangan Anggota</h1>
            <p class="text-sm text-slate-500">
                Pantau grafik performa skor tembakan dan volume latihan masing-masing pemanah (Periode: <strong>{{ $bulanIni->translatedFormat('F Y') }}</strong>).
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Nama Anggota</th>
                        <th class="px-4 py-3.5 text-center">Status Analisis</th>
                        <th class="px-4 py-3.5 text-right">Rata-rata Bulan Ini</th>
                        <th class="px-4 py-3.5 text-right">Rata-rata Bulan Lalu</th>
                        <th class="px-4 py-3.5 text-center">Selisih</th>
                        <th class="px-4 py-3.5 text-center">Sesi</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($anggotaList as $a)
                        @php
                            $st = $a->analisis;
                            $badge = match ($st['status']) {
                                'Naik'                 => 'bg-emerald-100 text-emerald-800',
                                'Tetap'                => 'bg-blue-100 text-blue-800',
                                'Turun'                => 'bg-rose-100 text-rose-800',
                                'Jarang Latihan'       => 'bg-amber-100 text-amber-800',
                                default                => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $a->nama }}</div>
                                <div class="text-xs text-slate-400">{{ $a->jenis_keanggotaan }} · {{ $a->jenis_kelamin === 'L' ? 'Ikhwan' : 'Akhwat' }}</div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2.5 py-1 text-xs font-extrabold rounded-full {{ $badge }}">
                                    {{ $st['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-bold text-slate-800">
                                {{ $st['rata_ini'] !== null ? number_format($st['rata_ini'], 1) : '-' }}
                            </td>
                            <td class="px-4 py-4 text-right font-mono text-slate-400">
                                {{ $st['rata_lalu'] !== null ? number_format($st['rata_lalu'], 1) : '-' }}
                            </td>
                            <td class="px-4 py-4 text-center font-mono font-bold">
                                @if ($st['selisih'] !== null)
                                    <span class="{{ $st['selisih'] > 0 ? 'text-emerald-600' : ($st['selisih'] < 0 ? 'text-rose-600' : 'text-slate-500') }}">
                                        {{ $st['selisih'] > 0 ? '+' : '' }}{{ number_format($st['selisih'], 1) }}
                                    </span>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center font-semibold text-slate-700">
                                {{ $st['jumlah_sesi'] }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.progres.show', $a) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition">
                                    Grafik & Log →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
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
