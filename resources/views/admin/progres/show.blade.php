@extends('layouts.admin')

@section('title', 'Progres Pemanah — ' . $anggota->nama)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.progres') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            ← Kembali ke Ringkasan Progres
        </a>
        <a href="{{ route('admin.progres.pdf', $anggota) }}" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-sm inline-flex items-center gap-1.5">
            <span>📄</span> Unduh Rapor Evaluasi PDF
        </a>
    </div>

    {{-- Banner Anggota & Status Perkembangan --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Performa Anggota</span>
            <h1 class="text-2xl font-black text-slate-800 mt-1">{{ $anggota->nama }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Kategori: {{ ucfirst($anggota->jenis_keanggotaan) }} · Busur: {{ $anggota->jenis_busur ?: 'Belum punya' }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <div class="p-4 bg-slate-50 rounded-2xl text-center">
                <span class="text-[10px] font-bold text-slate-400 uppercase block">Rata-rata Bulan Ini</span>
                <span class="text-xl font-black text-slate-800 font-mono">
                    {{ $analisis['rata_ini'] !== null ? number_format($analisis['rata_ini'], 1) : '-' }}
                </span>
            </div>
            <div class="p-4 bg-slate-50 rounded-2xl text-center">
                <span class="text-[10px] font-bold text-slate-400 uppercase block">Status</span>
                <span class="inline-block mt-0.5 px-3 py-1 text-xs font-extrabold rounded-full bg-emerald-100 text-emerald-800">
                    {{ $analisis['status'] }}
                </span>
            </div>
        </div>
    </div>

    {{-- Log Skor Tembakan Valid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Riwayat Skor Tembakan Valid</h2>
                <span class="text-xs text-slate-400 font-semibold">{{ $riwayatSkor->count() }} Sesi Tercatat</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-400 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-4 py-3">Sesi</th>
                            <th class="px-4 py-3">Jarak</th>
                            <th class="px-4 py-3">Qty Panah</th>
                            <th class="px-6 py-3 text-right">Total Skor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($riwayatSkor as $skor)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-6 py-3 font-semibold text-slate-800 text-xs">
                                    {{ $skor->tanggal->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-600">
                                    Sesi ke-{{ $skor->sesi_ke }}
                                </td>
                                <td class="px-4 py-3 text-xs font-bold text-slate-700">
                                    {{ $skor->jarak_meter }}m
                                </td>
                                <td class="px-4 py-3 text-xs font-mono font-semibold text-slate-600">
                                    {{ $skor->qty_panah }} panah
                                </td>
                                <td class="px-6 py-3 text-right font-mono font-bold text-emerald-700">
                                    {{ $skor->total_skor }} poin
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs">
                                    Belum ada sesi tembakan valid.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Log QtyShoot Harian & Target --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Volume Panah Harian (Qty Shoot)</h2>
                <div class="space-y-2">
                    @forelse ($qtyShootTerbaru as $q)
                        <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-xl text-xs">
                            <span class="text-slate-600 font-semibold">{{ $q->tanggal->translatedFormat('d M Y') }}</span>
                            <span class="font-mono font-bold text-indigo-700">{{ $q->jumlah_anak_panah }} panah</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada catatan volume panah.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Target Latihan Personal</h2>
                <div class="space-y-2">
                    @forelse ($targetLatihan as $t)
                        <div class="p-3 bg-slate-50 rounded-xl text-xs space-y-1">
                            <div class="flex items-center justify-between font-bold text-slate-800">
                                <span class="capitalize">{{ str_replace('_', ' ', $t->jenis_target) }}</span>
                                <span class="text-emerald-600 font-mono">{{ $t->nilai_target }}</span>
                            </div>
                            <div class="text-[11px] text-slate-400 flex justify-between">
                                <span>{{ $t->periode_mulai->format('d/m') }} - {{ $t->periode_selesai->format('d/m/Y') }}</span>
                                <span class="capitalize font-semibold text-slate-600">{{ $t->status }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada target latihan yang diset.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
