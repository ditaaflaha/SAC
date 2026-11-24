@extends('layouts.admin')

@section('title', 'Progres & Riwayat Latihan Anggota')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Progres Perkembangan Anggota</h1>
            <p class="text-sm text-[#6B7280]">
                Pantau grafik performa skor tembakan dan volume latihan masing-masing pemanah (Periode: <strong class="text-[#1F2937]">{{ $bulanIni->translatedFormat('F Y') }}</strong>).
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[#6B7280]">
                <thead class="bg-[#FAFAFB] text-xs uppercase font-semibold text-[#6B7280] border-b border-[#E5E7EB]">
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
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse ($anggotaList as $a)
                        @php
                            $st = $a->analisis;
                            $badge = match ($st['status']) {
                                'Naik'                 => 'bg-emerald-50 text-emerald-700',
                                'Tetap'                => 'bg-sky-50 text-sky-700',
                                'Turun'                => 'bg-rose-50 text-rose-700',
                                'Jarang Latihan'       => 'bg-amber-50 text-amber-700',
                                default                => 'bg-gray-100 text-[#6B7280]',
                            };
                        @endphp
                        <tr class="hover:bg-primary-50/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#1F2937]">{{ $a->nama }}</div>
                                <div class="text-xs text-[#6B7280]">{{ $a->jenis_keanggotaan }} · {{ $a->jenis_kelamin === 'L' ? 'Ikhwan' : 'Akhwat' }}</div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $badge }}">
                                    {{ $st['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-bold text-[#1F2937]">
                                {{ $st['rata_ini'] !== null ? number_format($st['rata_ini'], 1) : '-' }}
                            </td>
                            <td class="px-4 py-4 text-right font-mono text-[#6B7280]">
                                {{ $st['rata_lalu'] !== null ? number_format($st['rata_lalu'], 1) : '-' }}
                            </td>
                            <td class="px-4 py-4 text-center font-mono font-bold">
                                @if ($st['selisih'] !== null)
                                    <span class="{{ $st['selisih'] > 0 ? 'text-emerald-600' : ($st['selisih'] < 0 ? 'text-rose-600' : 'text-[#6B7280]') }}">
                                        {{ $st['selisih'] > 0 ? '+' : '' }}{{ number_format($st['selisih'], 1) }}
                                    </span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center font-semibold text-[#1F2937]">
                                {{ $st['jumlah_sesi'] }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.progres.show', $a) }}" class="inline-flex items-center px-3 py-1.5 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-xl text-xs font-semibold transition">
                                    Grafik & Log →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#6B7280]">
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
