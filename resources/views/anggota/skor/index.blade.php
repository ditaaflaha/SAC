@extends('layouts.anggota')

@section('title', 'Input Skor & Qty Shoot')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Input Skor & Qty Shoot</h1>
        <p class="text-sm text-[#6B7280]">Catat hasil tembakan sesi latihanmu dan jumlah anak panah harian untuk evaluasi progres.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Input Skor Tembakan --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-xl">🎯</span>
                <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Input Skor Sesi Latihan</h2>
            </div>

            <form method="POST" action="{{ route('anggota.skor.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#1F2937] mb-1">Tanggal Latihan <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#1F2937] mb-1">Sesi Ke- <span class="text-rose-500">*</span></label>
                        <input type="number" name="sesi_ke" value="{{ old('sesi_ke', 1) }}" min="1" max="10" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 font-mono focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#1F2937] mb-1">Jarak Target <span class="text-rose-500">*</span></label>
                        <select name="jarak_meter" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="5" {{ old('jarak_meter') == 5 ? 'selected' : '' }}>5 Meter</option>
                            <option value="10" {{ old('jarak_meter') == 10 ? 'selected' : '' }}>10 Meter</option>
                            <option value="18" {{ old('jarak_meter', 18) == 18 ? 'selected' : '' }}>18 Meter (Standar)</option>
                            <option value="30" {{ old('jarak_meter') == 30 ? 'selected' : '' }}>30 Meter</option>
                            <option value="50" {{ old('jarak_meter') == 50 ? 'selected' : '' }}>50 Meter</option>
                            <option value="70" {{ old('jarak_meter') == 70 ? 'selected' : '' }}>70 Meter</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#1F2937] mb-1">Qty Anak Panah <span class="text-rose-500">*</span></label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="jumlah_anak_panah" value="{{ old('jumlah_anak_panah', 36) }}" min="1" max="500" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 font-mono font-bold focus:ring-primary-500 focus:border-primary-500">
                            <span class="text-xs text-[#6B7280] font-semibold">Panah</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#1F2937] mb-1">Total Skor Tembakan <span class="text-rose-500">*</span></label>
                        <input type="number" name="total_skor" value="{{ old('total_skor') }}" placeholder="Contoh: 305" min="0" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 font-mono font-bold text-[#1F2937] focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Catatan / Kondisi Latihan (Opsional)</label>
                    <input type="text" name="catatan" value="{{ old('catatan') }}" placeholder="Misal: angin kencang, coba tab baru, fokus release..." class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                    Simpan & Kirim Skor untuk Divalidasi 🏹
                </button>
            </form>
        </div>

        {{-- Form Catat Qty Shoot Harian --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-xl">🏹</span>
                    <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Volume Panah Harian</h2>
                </div>

                <p class="text-xs text-[#6B7280] mb-4 leading-relaxed">
                    Catat total anak panah yang kamu lepaskan hari ini (termasuk drill, blank bale, dan latihan mandiri).
                </p>

                <form method="POST" action="{{ route('anggota.skor.qty.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-[#1F2937] mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ now()->toDateString() }}" max="{{ now()->toDateString() }}" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#1F2937] mb-1">Jumlah Anak Panah</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="jumlah_anak_panah" value="{{ old('jumlah_anak_panah', $qtyHariIni?->jumlah_anak_panah ?? 36) }}" min="1" max="500" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 font-mono font-bold focus:ring-primary-500 focus:border-primary-500">
                            <span class="text-xs text-[#6B7280] font-semibold">Panah</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-xl text-xs font-semibold transition">
                        Simpan Volume Harian
                    </button>
                </form>
            </div>

            {{-- Riwayat Qty Terakhir --}}
            <div class="border-t border-[#E5E7EB] pt-4 mt-6">
                <span class="text-xs font-bold text-[#1F2937] block mb-2">Riwayat Volume Terakhir:</span>
                <div class="space-y-1.5 max-h-36 overflow-y-auto">
                    @forelse ($riwayatQty as $rq)
                        <div class="flex justify-between text-xs py-1.5 px-2.5 bg-[#FAFAFB] rounded-lg border border-[#E5E7EB]">
                            <span class="text-[#6B7280]">{{ $rq->tanggal->translatedFormat('d M Y') }}</span>
                            <span class="font-bold text-primary-700 font-mono">{{ $rq->jumlah_anak_panah }} panah</span>
                        </div>
                    @empty
                        <p class="text-[11px] text-[#6B7280]">Belum ada catatan volume.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat Skor Saya --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-[#E5E7EB]">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Riwayat Skor Tembakan Saya</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[#6B7280]">
                <thead class="bg-[#FAFAFB] text-xs uppercase font-semibold text-[#6B7280] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-3.5">Tanggal</th>
                        <th class="px-4 py-3.5">Sesi</th>
                        <th class="px-4 py-3.5">Jarak Target</th>
                        <th class="px-4 py-3.5">Qty Panah</th>
                        <th class="px-4 py-3.5 text-right">Total Skor</th>
                        <th class="px-4 py-3.5 text-center">Status Validasi</th>
                        <th class="px-6 py-3.5">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse ($riwayatSkor as $skor)
                        <tr class="hover:bg-primary-50/30 transition">
                            <td class="px-6 py-4 font-semibold text-[#1F2937] text-xs">
                                {{ $skor->tanggal->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 py-4 text-xs font-semibold text-[#1F2937]">
                                Sesi ke-{{ $skor->sesi_ke }}
                            </td>
                            <td class="px-4 py-4 text-xs font-bold text-[#1F2937]">
                                {{ $skor->jarak_meter }} meter
                            </td>
                            <td class="px-4 py-4 text-xs font-mono font-semibold text-[#6B7280]">
                                {{ $skor->qty_panah }} panah
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-extrabold text-[#1F2937] text-base">
                                {{ $skor->total_skor }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $bClass = match ($skor->status_validasi) {
                                        'valid'    => 'bg-emerald-50 text-emerald-700',
                                        'menunggu' => 'bg-amber-50 text-amber-700',
                                        'ditolak'  => 'bg-rose-50 text-rose-700',
                                        default    => 'bg-gray-100 text-[#6B7280]',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $bClass }}">
                                    {{ ucfirst($skor->status_validasi) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-[#6B7280] max-w-xs truncate">
                                {{ $skor->catatan ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-[#6B7280] text-xs">
                                Belum ada riwayat skor tembakan yang diinput.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#E5E7EB]">
            {{ $riwayatSkor->links() }}
        </div>
    </div>
</div>
@endsection
