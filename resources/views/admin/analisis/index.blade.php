@extends('layouts.admin')

@section('title', 'Dashboard Analisis Perkembangan Komunitas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Analisis Komunitas</h1>
            <p class="text-sm text-slate-500">Evaluasi menyeluruh kenaikan/penurunan skor dan tindak lanjut peringatan pelatih.</p>
        </div>

        <form method="GET" action="{{ route('admin.analisis') }}" class="flex items-center gap-2">
            <span class="text-xs font-semibold text-slate-500">Bulan:</span>
            <input type="month" name="bulan" value="{{ $bulanStr }}" onchange="this.form.submit()"
                   class="text-xs border-slate-200 rounded-xl px-3 py-1.5 focus:ring-emerald-500">
        </form>
    </div>

    {{-- Daftar Peringatan yang Membutuhkan Perhatian Pelatih --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                    Daftar Peringatan & Evaluasi Pelatih
                </h2>
                <p class="text-xs text-slate-400">Pemanah dengan status Tetap, Turun, atau Jarang Latihan yang perlu intervensi</p>
            </div>
            <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full font-bold text-xs">
                {{ $daftarPeringatan->where('sudah_ditindaklanjuti', false)->count() }} Belum Ditindaklanjuti
            </span>
        </div>

        <div class="space-y-3">
            @forelse ($daftarPeringatan as $p)
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 rounded-xl border {{ $p->sudah_ditindaklanjuti ? 'bg-slate-50 border-slate-200 opacity-60' : 'bg-amber-50/50 border-amber-200' }} gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-800">{{ $p->anggota->nama }}</span>
                            <span class="px-2 py-0.5 text-[11px] font-extrabold rounded-full {{ $p->status === 'Turun' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $p->status }}
                            </span>
                            @if ($p->sudah_ditindaklanjuti)
                                <span class="px-2 py-0.5 text-[10px] bg-emerald-100 text-emerald-800 rounded-full font-bold">
                                    ✓ Selesai Ditindaklanjuti
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            💡 Rekomendasi: {{ $p->rekomendasi ?: 'Perlu evaluasi langsung oleh pelatih.' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <form method="POST" action="{{ route('admin.analisis.tindaklanjuti', $p) }}">
                            @csrf
                            <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $p->sudah_ditindaklanjuti ? 'bg-slate-200 text-slate-700 hover:bg-slate-300' : 'bg-amber-600 text-white hover:bg-amber-700 shadow-sm' }}">
                                {{ $p->sudah_ditindaklanjuti ? 'Tandai Belum' : '✓ Tandai Sudah Ditindaklanjuti' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.analisis.destroy', $p) }}" onsubmit="return confirm('Hapus catatan peringatan/rekomendasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold transition">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 py-4 text-center">Tidak ada peringatan untuk periode bulan ini.</p>
            @endforelse
        </div>
    </div>

    {{-- Rekap Seluruh Anggota --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                Rekap Analisis Komunitas ({{ $bulan->translatedFormat('F Y') }})
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Nama Pemanah</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Sesi</th>
                        <th class="px-4 py-3.5 text-right">Rata Lalu</th>
                        <th class="px-4 py-3.5 text-right">Rata Ini</th>
                        <th class="px-4 py-3.5 text-center">Selisih</th>
                        <th class="px-6 py-3.5">Rekomendasi Terkirim</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($rekapKomunitas as $item)
                        @php
                            $badge = match ($item['status']) {
                                'Naik'                 => 'bg-emerald-100 text-emerald-800',
                                'Tetap'                => 'bg-blue-100 text-blue-800',
                                'Turun'                => 'bg-rose-100 text-rose-800',
                                'Jarang Latihan'       => 'bg-amber-100 text-amber-800',
                                default                => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-3.5">
                                <div class="font-bold text-slate-800">{{ $item['anggota']->nama }}</div>
                                <div class="text-xs text-slate-400">{{ $item['anggota']->jenis_keanggotaan }}</div>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $badge }}">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center font-semibold">
                                {{ $item['jumlah_sesi'] }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-mono text-slate-400">
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
                            <td class="px-6 py-3.5 text-xs text-slate-500 max-w-sm truncate" title="{{ $item['saran'] }}">
                                {{ $item['saran'] ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-400">
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
