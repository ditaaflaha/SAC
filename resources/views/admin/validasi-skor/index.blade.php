@extends('layouts.admin')

@section('title', 'Validasi Skor Tembakan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Validasi Skor Tembakan</h1>
            <p class="text-sm text-slate-500">Tinjau dan verifikasi skor hasil latihan harian yang disubmit oleh anggota.</p>
        </div>
    </div>

    {{-- Filter Status Tabs --}}
    <div class="flex gap-2 border-b border-slate-200 pb-3">
        @foreach ([
            ['menunggu', 'Menunggu Validasi', $counts['menunggu'], 'bg-amber-100 text-amber-800'],
            ['valid', 'Sudah Valid', $counts['valid'], 'bg-emerald-100 text-emerald-800'],
            ['ditolak', 'Ditolak', $counts['ditolak'], 'bg-rose-100 text-rose-800'],
            ['semua', 'Semua Data', $counts['semua'], 'bg-slate-100 text-slate-700'],
        ] as [$val, $label, $count, $badgeClass])
            <a href="{{ route('admin.validasi-skor', ['status' => $val]) }}"
               class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2
                      {{ $status === $val ? 'bg-slate-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
                <span>{{ $label }}</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ $badgeClass }}">{{ $count }}</span>
            </a>
        @endforeach
    </div>

    {{-- Tabel Skor Tembakan --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Nama Anggota</th>
                        <th class="px-4 py-3.5">Tanggal & Sesi</th>
                        <th class="px-4 py-3.5">Format Tembakan</th>
                        <th class="px-4 py-3.5 text-right">Total Skor</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-6 py-3.5">Catatan Anggota</th>
                        <th class="px-6 py-3.5 text-right">Aksi Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($skorList as $skor)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $skor->anggota->nama }}</div>
                                <div class="text-xs text-slate-400">{{ $skor->anggota->jenis_keanggotaan }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <div class="font-bold text-slate-700">{{ $skor->tanggal->translatedFormat('d M Y') }}</div>
                                <div class="text-slate-400">Sesi ke-{{ $skor->sesi_ke }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <div><strong class="text-slate-700">{{ $skor->jarak_meter }} meter</strong></div>
                                <div class="text-slate-400">Qty: {{ $skor->qty_panah }} panah</div>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="font-mono font-black text-lg text-slate-900">{{ $skor->total_skor }}</span>
                                <span class="text-xs text-slate-400 block">poin</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $sBadge = match ($skor->status_validasi) {
                                        'valid'    => 'bg-emerald-100 text-emerald-800',
                                        'menunggu' => 'bg-amber-100 text-amber-800 animate-pulse',
                                        'ditolak'  => 'bg-rose-100 text-rose-800',
                                        default    => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $sBadge }}">
                                    {{ ucfirst($skor->status_validasi) }}
                                </span>
                                @if ($skor->validator)
                                    <div class="text-[10px] text-slate-400 mt-1">oleh {{ $skor->validator->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 max-w-xs">
                                {{ $skor->catatan ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                                @if ($skor->status_validasi === 'menunggu')
                                    <form method="POST" action="{{ route('admin.validasi-skor.update', $skor) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_validasi" value="valid">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                                            ✓ Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.validasi-skor.update', $skor) }}" class="inline" onsubmit="return confirm('Tolak skor ini?')">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_validasi" value="ditolak">
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 font-bold text-xs rounded-xl transition">
                                            ✕ Tolak
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400 italic">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada skor tembakan pada tab ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $skorList->links() }}
        </div>
    </div>
</div>
@endsection
