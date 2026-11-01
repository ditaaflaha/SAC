@extends('layouts.anggota')

@section('title', 'Leaderboard Komunitas SAC')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Leaderboard Pemanah SAC</h1>
        <p class="text-sm text-gray-500">Peringkat klasemen pemanah Sunnah Archery Community berdasarkan rata-rata skor tembakan valid.</p>
    </div>

    {{-- Kartu Posisi Saya --}}
    @if ($peringkatSaya)
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-6 text-white shadow-md flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center font-black text-2xl font-mono">
                    #{{ $peringkatSaya }}
                </div>
                <div>
                    <span class="text-xs text-emerald-100 uppercase tracking-wider block">Peringkat Kamu di Jarak {{ $jarak }}m</span>
                    <h2 class="text-xl font-bold">{{ $anggota->nama }}</h2>
                    <p class="text-xs text-emerald-200 mt-0.5">Tetap semangat tingkatkan akurasi dan konsistensi form! 🏹</p>
                </div>
            </div>
            <div class="hidden sm:block text-4xl">
                🏆
            </div>
        </div>
    @endif

    {{-- Filter Jarak & Periode --}}
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('anggota.leaderboard') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-gray-500">Pilih Jarak:</span>
                <div class="flex gap-1.5">
                    @foreach ($jarakOptions as $opt)
                        <button type="submit" name="jarak" value="{{ $opt }}"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition
                                       {{ (int)$jarak === $opt ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $opt }}m
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-2 ml-auto">
                <span class="text-xs font-semibold text-gray-500">Bulan:</span>
                <input type="month" name="bulan" value="{{ $bulanStr }}" onchange="this.form.submit()"
                       class="text-xs border-gray-200 rounded-xl px-3 py-1.5 focus:ring-emerald-500">
            </div>
        </form>
    </div>

    {{-- Tabel Klasemen --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
                Klasemen Target {{ $jarak }} Meter
            </h2>
            <span class="text-xs text-gray-400 font-semibold">{{ $rankings->count() }} Pemanah Terdaftar</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-400 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-center w-16">Peringkat</th>
                        <th class="px-6 py-3.5">Nama Pemanah</th>
                        <th class="px-4 py-3.5 text-center">Sesi Valid</th>
                        <th class="px-4 py-3.5 text-right">Skor Tertinggi</th>
                        <th class="px-6 py-3.5 text-right">Rata-rata Skor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($rankings as $idx => $r)
                        @php
                            $rank = $idx + 1;
                            $isMe = $r->anggota_id === $anggota->id;
                            $medal = match ($rank) {
                                1 => '🥇',
                                2 => '🥈',
                                3 => '🥉',
                                default => null,
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition {{ $isMe ? 'bg-emerald-50/50 font-semibold' : ($rank <= 3 ? 'bg-amber-50/20' : '') }}">
                            <td class="px-6 py-4 text-center font-black">
                                @if ($medal)
                                    <span class="text-lg">{{ $medal }}</span>
                                @else
                                    <span class="text-gray-400 font-mono text-sm">#{{ $rank }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 flex items-center gap-2">
                                    <span>{{ $r->anggota->nama }}</span>
                                    @if ($isMe)
                                        <span class="px-2 py-0.5 text-[10px] font-black rounded-full bg-emerald-600 text-white">
                                            SAYA
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-400">{{ $r->anggota->jenis_keanggotaan }}</div>
                            </td>
                            <td class="px-4 py-4 text-center text-gray-700 font-semibold">
                                {{ $r->total_sesi }}
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-bold text-gray-700">
                                {{ $r->skor_tertinggi }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-mono font-black text-emerald-600 text-lg">
                                    {{ number_format($r->rata_skor, 1) }}
                                </span>
                                <span class="text-xs text-gray-400 block">poin</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-xs">
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

