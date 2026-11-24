@extends('layouts.admin')

@section('title', 'Pengaturan Parameter Analisis')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Pengaturan Parameter Analisis</h1>
            <p class="text-sm text-[#6B7280]">Konfigurasi batas toleransi poin, batas minimal kehadiran latihan, dan template saran pelatih.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm max-w-4xl">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">
                        Batas Toleransi Poin (Batas Naik/Turun)
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="batas_poin" value="{{ old('batas_poin', $pengaturan->batas_poin) }}" required min="1" max="50" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 font-mono focus:ring-primary-500 focus:border-primary-500">
                        <span class="text-xs text-[#6B7280] font-semibold">Poin</span>
                    </div>
                    <p class="text-[11px] text-[#6B7280] mt-1">Selisih ≥ nilai ini diklasifikasikan 'Naik' / 'Turun'. Di bawahnya dihitung 'Tetap'.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">
                        Minimal Latihan Bulanan
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="min_latihan_bulanan" value="{{ old('min_latihan_bulanan', $pengaturan->min_latihan_bulanan) }}" required min="1" max="31" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 font-mono focus:ring-primary-500 focus:border-primary-500">
                        <span class="text-xs text-[#6B7280] font-semibold">Sesi</span>
                    </div>
                    <p class="text-[11px] text-[#6B7280] mt-1">Sesi di bawah jumlah ini otomatis berstatus 'Jarang Latihan'.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">
                        Standar Anak Panah Per Sesi
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="anak_panah_per_sesi" value="{{ old('anak_panah_per_sesi', $pengaturan->anak_panah_per_sesi) }}" required min="1" max="200" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 font-mono focus:ring-primary-500 focus:border-primary-500">
                        <span class="text-xs text-[#6B7280] font-semibold">Panah</span>
                    </div>
                    <p class="text-[11px] text-[#6B7280] mt-1">Standar tembakan per sesi scoring (default: 36 panah).</p>
                </div>
            </div>

            <div class="border-t border-[#E5E7EB] pt-6 space-y-4">
                <h3 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Template Saran & Rekomendasi Pelatih</h3>

                <div>
                    <label class="block text-xs font-semibold text-emerald-700 mb-1">Saran untuk Status "Naik" (Peningkatan Skor)</label>
                    <textarea name="saran_naik" rows="2" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">{{ old('saran_naik', $pengaturan->saran_naik) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-sky-700 mb-1">Saran untuk Status "Tetap" (Skor Stabil / Plateau)</label>
                    <textarea name="saran_tetap" rows="2" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">{{ old('saran_tetap', $pengaturan->saran_tetap) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-rose-700 mb-1">Saran untuk Status "Turun" (Penurunan Skor)</label>
                    <textarea name="saran_turun" rows="2" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">{{ old('saran_turun', $pengaturan->saran_turun) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-amber-700 mb-1">Saran untuk Status "Jarang Latihan"</label>
                    <textarea name="saran_jarang" rows="2" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">{{ old('saran_jarang', $pengaturan->saran_jarang) }}</textarea>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                    Simpan Perubahan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
