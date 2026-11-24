@extends('layouts.anggota')

@section('title', 'Target & Rekomendasi Latihan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Target & Rekomendasi Latihan</h1>
        <p class="text-sm text-[#6B7280]">Tetapkan target personal dan pantau evaluasi serta catatan dari pelatih.</p>
    </div>

    {{-- Rekomendasi Pelatih --}}
    @if (empty($sembunyikanRekomendasi) && (!empty($status['saran']) || $rekomendasiPelatih))
        <div class="bg-primary-50/70 border border-primary-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <span class="text-3xl flex-shrink-0">💡</span>
                    <div class="space-y-2">
                        <h2 class="text-base font-bold text-primary-900">Catatan & Rekomendasi Pelatih untuk Kamu</h2>
                        <p class="text-sm text-[#1F2937] leading-relaxed">
                            {{ $status['saran'] ?: ($rekomendasiPelatih?->rekomendasi ?? 'Pertahankan konsistensi latihan rutin mingguan.') }}
                        </p>
                        <div class="text-xs text-[#6B7280] font-medium">
                            Status saat ini: <strong class="uppercase font-bold text-primary-700">{{ $status['status'] }}</strong> (Rata-rata: {{ $status['rata_ini'] !== null ? number_format($status['rata_ini'], 1) : '-' }} poin)
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('anggota.target.rekomendasi.destroy') }}" onsubmit="return confirm('Hapus catatan rekomendasi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-white border border-[#E5E7EB] hover:bg-rose-50 text-[#6B7280] hover:text-rose-700 text-xs font-semibold transition flex-shrink-0">
                        🗑️ Hapus
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Buat Target Baru --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider mb-4">Buat Target Personal Baru</h2>
            <form method="POST" action="{{ route('anggota.target.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Jenis Target <span class="text-rose-500">*</span></label>
                    <select name="jenis_target" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="rata_rata_skor">Rata-rata Skor (Poin)</option>
                        <option value="qty_shoot">Volume Panah Bulanan (Panah)</option>
                        <option value="kehadiran">Kehadiran Latihan (Sesi)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Nilai Target yang Ingin Dicapai <span class="text-rose-500">*</span></label>
                    <input type="number" name="nilai_target" placeholder="Contoh: 310 (poin) atau 300 (panah)" required min="1" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 font-mono font-bold focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-[#1F2937] mb-1">Mulai <span class="text-rose-500">*</span></label>
                        <input type="date" name="periode_mulai" value="{{ now()->startOfMonth()->toDateString() }}" required class="w-full text-xs border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[#1F2937] mb-1">Selesai <span class="text-rose-500">*</span></label>
                        <input type="date" name="periode_selesai" value="{{ now()->endOfMonth()->toDateString() }}" required class="w-full text-xs border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                    🎯 Pasang Target
                </button>
            </form>
        </div>

        {{-- Daftar Target Latihan --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6 space-y-4">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Daftar Target Latihan Saya</h2>
            <div class="space-y-3">
                @forelse ($targetList as $t)
                    <div class="p-4 rounded-xl border border-[#E5E7EB] bg-[#FAFAFB] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-[#1F2937] capitalize">
                                    {{ str_replace('_', ' ', $t->jenis_target) }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $t->status === 'tercapai' ? 'bg-emerald-50 text-emerald-700' : ($t->status === 'berjalan' ? 'bg-primary-100 text-primary-700' : 'bg-rose-50 text-rose-700') }}">
                                    {{ ucfirst(str_replace('_', ' ', $t->status)) }}
                                </span>
                            </div>
                            <div class="text-xs text-[#6B7280]">
                                Periode: {{ $t->periode_mulai->format('d/m/Y') }} sampai {{ $t->periode_selesai->format('d/m/Y') }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-4">
                            <div class="text-right">
                                <span class="text-xs text-[#6B7280] block">Sasaran:</span>
                                <span class="text-xl font-extrabold text-primary-600 font-mono">
                                    {{ $t->nilai_target }}
                                </span>
                                <span class="text-xs text-[#6B7280]">
                                    {{ $t->jenis_target === 'rata_rata_skor' ? 'poin' : ($t->jenis_target === 'qty_shoot' ? 'panah' : 'sesi') }}
                                </span>
                            </div>

                            <form method="POST" action="{{ route('anggota.target.destroy', $t) }}" onsubmit="return confirm('Yakin ingin menghapus target latihan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold transition">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#6B7280] text-center py-10">Belum ada target latihan yang kamu tetapkan. Buat sekarang untuk memotivasi latihanmu!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
