@extends('layouts.admin')

@section('title', 'Leaderboard Komunitas SAC')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Leaderboard Peringkat Komunitas</h1>
            <p class="text-sm text-slate-500">Peringkat pemanah berdasarkan rata-rata skor tembakan valid per jarak target.</p>
        </div>
    </div>

    {{-- Filter Jarak & Bulan --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin.leaderboard') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-500">Pilih Jarak:</span>
                <div class="flex gap-1.5">
                    @foreach ($jarakOptions as $opt)
                        <button type="submit" name="jarak" value="{{ $opt }}"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition
                                       {{ (int)$jarak === $opt ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            {{ $opt }}m
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-2 ml-auto">
                <span class="text-xs font-semibold text-slate-500">Bulan:</span>
                <input type="month" name="bulan" value="{{ $bulanStr }}" onchange="this.form.submit()"
                       class="text-xs border-slate-200 rounded-xl px-3 py-1.5 focus:ring-emerald-500">
            </div>
        </form>
    </div>

    {{-- Tabel Klasemen --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                Klasemen Jarak {{ $jarak }} Meter
            </h2>
            <span class="text-xs text-slate-400 font-semibold">{{ $rankings->count() }} Pemanah Terdata</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5 text-center w-16">Peringkat</th>
                        <th class="px-6 py-3.5">Nama Pemanah</th>
                        <th class="px-4 py-3.5 text-center">Jumlah Sesi</th>
                        <th class="px-4 py-3.5 text-right">Skor Tertinggi</th>
                        <th class="px-6 py-3.5 text-right">Rata-rata Skor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
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
                        <tr class="hover:bg-slate-50/50 transition {{ $rank <= 3 ? 'bg-amber-50/20' : '' }}">
                            <td class="px-6 py-4 text-center font-black">
                                @if ($medal)
                                    <span class="text-lg">{{ $medal }}</span>
                                @else
                                    <span class="text-slate-400 font-mono text-sm">#{{ $rank }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $r->anggota->nama }}</div>
                                <div class="text-xs text-slate-400">{{ $r->anggota->jenis_keanggotaan }} · {{ $r->anggota->jenis_kelamin === 'L' ? 'Ikhwan' : 'Akhwat' }}</div>
                            </td>
                            <td class="px-4 py-4 text-center font-semibold text-slate-700">
                                {{ $r->total_sesi }}
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-bold text-slate-700">
                                {{ $r->skor_tertinggi }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-mono font-black text-emerald-600 text-lg">
                                    {{ number_format($r->rata_skor, 1) }}
                                </span>
                                <span class="text-xs text-slate-400 block">poin</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
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
