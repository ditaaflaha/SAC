@extends('layouts.admin')

@section('title', 'Leaderboard Komunitas SAC')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Leaderboard Peringkat Komunitas</h1>
            <p class="text-sm text-[#6B7280]">Peringkat pemanah berdasarkan rata-rata skor tembakan valid per jarak target.</p>
        </div>
    </div>

    {{-- Filter Jarak & Bulan --}}
    <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-sm">
        <form method="GET" action="{{ route('admin.leaderboard') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-[#6B7280]">Pilih Jarak:</span>
                <div class="flex gap-1.5">
                    @foreach ($jarakOptions as $opt)
                        <button type="submit" name="jarak" value="{{ $opt }}"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition
                                       {{ (int)$jarak === $opt ? 'bg-primary-600 text-white shadow-sm' : 'bg-primary-50 text-primary-700 hover:bg-primary-100' }}">
                            {{ $opt }}m
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-2 ml-auto">
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
                Klasemen Jarak {{ $jarak }} Meter
            </h2>
            <span class="text-xs text-[#6B7280] font-semibold">{{ $rankings->count() }} Pemanah Terdata</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[#6B7280]">
                <thead class="bg-[#FAFAFB] text-xs uppercase font-semibold text-[#6B7280] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-3.5 text-center w-16">Peringkat</th>
                        <th class="px-6 py-3.5">Nama Pemanah</th>
                        <th class="px-4 py-3.5 text-center">Jumlah Sesi</th>
                        <th class="px-4 py-3.5 text-right">Skor Tertinggi</th>
                        <th class="px-6 py-3.5 text-right">Rata-rata Skor</th>
                    </tr>
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
                        <tr class="hover:bg-primary-50/30 transition {{ $rank <= 3 ? 'bg-primary-50/30' : '' }}">
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[#6B7280]">
                                Belum ada catatan skor valid untuk jarak {{ $jarak }}m pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
