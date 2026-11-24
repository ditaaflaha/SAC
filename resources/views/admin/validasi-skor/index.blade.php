@extends('layouts.admin')

@section('title', 'Validasi Skor Tembakan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Validasi Skor Tembakan</h1>
            <p class="text-sm text-[#6B7280]">Tinjau dan verifikasi skor hasil latihan harian yang disubmit oleh anggota.</p>
        </div>
    </div>

    {{-- Filter Status Tabs --}}
    <div class="flex flex-wrap gap-2 border-b border-[#E5E7EB] pb-3">
        @foreach ([
            ['menunggu', 'Menunggu Validasi', $counts['menunggu'], 'bg-amber-50 text-amber-700'],
            ['valid', 'Sudah Valid', $counts['valid'], 'bg-emerald-50 text-emerald-700'],
            ['ditolak', 'Ditolak', $counts['ditolak'], 'bg-rose-50 text-rose-700'],
            ['semua', 'Semua Data', $counts['semua'], 'bg-primary-50 text-primary-700'],
        ] as [$val, $label, $count, $badgeClass])
            <a href="{{ route('admin.validasi-skor', ['status' => $val]) }}"
               class="px-4 py-2 rounded-xl text-xs font-semibold transition flex items-center gap-2
                      {{ $status === $val ? 'bg-primary-600 text-white shadow-sm' : 'bg-white border border-[#E5E7EB] text-[#6B7280] hover:bg-primary-50 hover:text-primary-700' }}">
                <span>{{ $label }}</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $status === $val ? 'bg-white/20 text-white' : $badgeClass }}">{{ $count }}</span>
            </a>
        @endforeach
    </div>

    {{-- Tabel Skor Tembakan --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[#6B7280]">
                <thead class="bg-[#FAFAFB] text-xs uppercase font-semibold text-[#6B7280] border-b border-[#E5E7EB]">
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
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse ($skorList as $skor)
                        <tr class="hover:bg-primary-50/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#1F2937]">{{ $skor->anggota->nama }}</div>
                                <div class="text-xs text-[#6B7280]">{{ $skor->anggota->jenis_keanggotaan }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <div class="font-bold text-[#1F2937]">{{ $skor->tanggal->translatedFormat('d M Y') }}</div>
                                <div class="text-[#6B7280]">Sesi ke-{{ $skor->sesi_ke }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <div><strong class="text-[#1F2937]">{{ $skor->jarak_meter }} meter</strong></div>
                                <div class="text-[#6B7280]">Qty: {{ $skor->qty_panah }} panah</div>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="font-mono font-extrabold text-lg text-[#1F2937]">{{ $skor->total_skor }}</span>
                                <span class="text-xs text-[#6B7280] block">poin</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $sBadge = match ($skor->status_validasi) {
                                        'valid'    => 'bg-emerald-50 text-emerald-700',
                                        'menunggu' => 'bg-amber-50 text-amber-700',
                                        'ditolak'  => 'bg-rose-50 text-rose-700',
                                        default    => 'bg-gray-100 text-[#6B7280]',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $sBadge }}">
                                    {{ ucfirst($skor->status_validasi) }}
                                </span>
                                @if ($skor->validator)
                                    <div class="text-[10px] text-[#6B7280] mt-1">oleh {{ $skor->validator->name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-[#6B7280] max-w-xs">
                                {{ $skor->catatan ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                                @if ($skor->status_validasi === 'menunggu')
                                    <form method="POST" action="{{ route('admin.validasi-skor.update', $skor) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_validasi" value="valid">
                                        <button type="submit" class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold text-xs rounded-xl transition shadow-sm">
                                            ✓ Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.validasi-skor.update', $skor) }}" class="inline" onsubmit="return confirm('Tolak skor ini?')">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_validasi" value="ditolak">
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 font-semibold text-xs rounded-xl transition">
                                            ✕ Tolak
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-[#6B7280] italic">Sudah diproses</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#6B7280]">
                                Tidak ada skor tembakan pada tab ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#E5E7EB]">
            {{ $skorList->links() }}
        </div>
    </div>
</div>
@endsection
