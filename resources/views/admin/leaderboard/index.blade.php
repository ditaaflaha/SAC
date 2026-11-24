@extends('layouts.admin')

@section('title', 'Leaderboard Komunitas SAC')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Leaderboard Peringkat Komunitas</h1>
            <p class="text-sm text-[#6B7280]">Klasemen pemanah SAC berdasarkan Top Skor, Paling Rajin (Volume Panah), dan Kehadiran.</p>
        </div>
    </div>

    {{-- 3 Tab Kategori (Di bawah judul, di atas filter) --}}
    <div class="flex flex-wrap gap-2">
        @foreach ([
            ['skor', '🎯 Top Skor'],
            ['rajin', '🏹 Paling Rajin'],
            ['hadir', '📅 Paling Sering Hadir'],
        ] as [$key, $label])
            <a href="{{ route('admin.leaderboard', array_filter(['kategori' => $key, 'bulan' => $bulanStr, 'jarak' => $key === 'skor' ? $jarak : null])) }}"
               class="px-4 py-2.5 rounded-xl text-xs font-semibold transition
                      {{ $kategori === $key ? 'bg-primary-600 text-white shadow-sm' : 'bg-white border border-[#E5E7EB] text-[#6B7280] hover:bg-primary-50 hover:text-primary-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Filter Jarak (Hanya Tab Top Skor) & Filter Bulan (Semua Tab) --}}
    <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-sm">
        <form method="GET" action="{{ route('admin.leaderboard') }}" class="flex flex-wrap items-center gap-4">
            <input type="hidden" name="kategori" value="{{ $kategori }}">

            @if ($kategori === 'skor')
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-semibold text-[#6B7280]">Pilih Jarak:</span>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($jarakOptions as $opt)
                            <button type="submit" name="jarak" value="{{ $opt }}"
                                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition
                                           {{ (int)$jarak === $opt ? 'bg-primary-600 text-white shadow-sm' : 'bg-primary-50 text-primary-700 hover:bg-primary-100' }}">
                                {{ $opt }}m
                            </button>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-xs text-[#6B7280] font-medium">
                    @if ($kategori === 'rajin')
                        Menampilkan peringkat total anak panah (Qty Shoot) pada bulan <strong class="text-[#1F2937]">{{ $bulan->translatedFormat('F Y') }}</strong>
                    @else
                        Menampilkan peringkat kehadiran latihan pada bulan <strong class="text-[#1F2937]">{{ $bulan->translatedFormat('F Y') }}</strong>
                    @endif
                </div>
            @endif

            <div class="flex items-center gap-2 ml-auto">
                @if ($kategori === 'skor')
                    <input type="hidden" name="jarak" value="{{ $jarak }}">
                @endif
                <span class="text-xs font-semibold text-[#6B7280]">Bulan:</span>
                <input type="month" name="bulan" value="{{ $bulanStr }}" onchange="this.form.submit()"
                       class="text-xs border-[#E5E7EB] rounded-xl px-3 py-1.5 focus:ring-primary-500 focus:border-primary-500">
            </div>
        </form>
    </div>

    {{-- Tabel Klasemen --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-[#E5E7EB] flex items-center justify-between">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">
                @if ($kategori === 'skor')
                    Top Skor — Target {{ $jarak }} Meter ({{ $bulan->translatedFormat('F Y') }})
                @elseif ($kategori === 'rajin')
                    Paling Rajin — Volume Anak Panah ({{ $bulan->translatedFormat('F Y') }})
                @else
                    Paling Sering Hadir — Presensi Latihan ({{ $bulan->translatedFormat('F Y') }})
                @endif
            </h2>
            <span class="text-xs text-[#6B7280] font-semibold">{{ $rankings->count() }} Pemanah Terdata</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[#6B7280]">
                <thead class="bg-[#FAFAFB] text-xs uppercase font-semibold text-[#6B7280] border-b border-[#E5E7EB]">
                    @if ($kategori === 'skor')
                        <tr>
                            <th class="px-6 py-3.5 text-center w-16">Peringkat</th>
                            <th class="px-6 py-3.5">Nama Pemanah</th>
                            <th class="px-4 py-3.5 text-center">Sesi Valid</th>
                            <th class="px-4 py-3.5 text-right">Skor Tertinggi</th>
                            <th class="px-6 py-3.5 text-right">Rata-rata Skor</th>
                        </tr>
                    @elseif ($kategori === 'rajin')
                        <tr>
                            <th class="px-6 py-3.5 text-center w-16">Peringkat</th>
                            <th class="px-6 py-3.5">Nama Pemanah</th>
                            <th class="px-4 py-3.5 text-center">Jumlah Hari Latihan</th>
                            <th class="px-6 py-3.5 text-right">Total Anak Panah</th>
                        </tr>
                    @else
                        <tr>
                            <th class="px-6 py-3.5 text-center w-16">Peringkat</th>
                            <th class="px-6 py-3.5">Nama Pemanah</th>
                            <th class="px-4 py-3.5 text-center">Jumlah Kehadiran</th>
                            <th class="px-6 py-3.5 text-right">Jadwal Bulan Ini</th>
                        </tr>
                    @endif
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse ($rankings as $idx => $r)
                        @php
                            $rank = $idx + 1;
                            $medal = match ($rank) {
                                1 => '🥇',
                                2 => '🥈',
                                3 => '🥉',
                                default => null,
                            };
                        @endphp
                        <tr class="hover:bg-primary-50/30 transition {{ $rank <= 3 ? 'bg-primary-50/20' : '' }}">
                            <td class="px-6 py-4 text-center font-bold">
                                @if ($medal)
                                    <span class="text-lg">{{ $medal }}</span>
                                @else
                                    <span class="text-[#6B7280] font-mono text-sm">#{{ $rank }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#1F2937]">{{ $r->anggota->nama }}</div>
                                <div class="text-xs text-[#6B7280]">{{ $r->anggota->jenis_keanggotaan }} · {{ $r->anggota->jenis_kelamin === 'L' ? 'Ikhwan' : 'Akhwat' }}</div>
                            </td>

                            @if ($kategori === 'skor')
                                <td class="px-4 py-4 text-center font-semibold text-[#1F2937]">
                                    {{ $r->total_sesi }}
                                </td>
                                <td class="px-4 py-4 text-right font-mono font-bold text-[#1F2937]">
                                    {{ $r->skor_tertinggi }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-mono font-extrabold text-primary-600 text-lg">
                                        {{ number_format($r->rata_skor, 1) }}
                                    </span>
                                    <span class="text-xs text-[#6B7280] block">poin</span>
                                </td>
                            @elseif ($kategori === 'rajin')
                                <td class="px-4 py-4 text-center font-semibold text-[#1F2937]">
                                    {{ $r->total_hari }} hari
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-mono font-extrabold text-primary-600 text-lg">
                                        {{ number_format($r->total_panah, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs text-[#6B7280] block">anak panah</span>
                                </td>
                            @else
                                <td class="px-4 py-4 text-center">
                                    <span class="font-mono font-extrabold text-primary-600 text-lg">
                                        {{ $r->total_hadir }}
                                    </span>
                                    <span class="text-xs text-[#6B7280] block">kali hadir</span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-semibold text-[#1F2937]">
                                    {{ $totalJadwalBulanIni }} jadwal
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $kategori === 'skor' ? 5 : 4 }}" class="px-6 py-12 text-center text-[#6B7280] text-xs">
                                Belum ada data peringkat pada kategori ini untuk periode {{ $bulan->translatedFormat('F Y') }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
