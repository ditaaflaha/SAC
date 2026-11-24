@extends('layouts.anggota')

@section('title', 'Progres Perkembangan Saya')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Progres Perkembangan Latihan</h1>
            <p class="text-sm text-[#6B7280]">Evaluasi perkembangan skor tembakan dan konsistensi latihan memanahmu.</p>
        </div>

        {{-- Form Pilih Bulan & Tombol Unduh Laporan PDF (Sejajar Judul Halaman) --}}
        <form method="GET" action="{{ route('anggota.progres.pdf') }}" class="flex items-center gap-2 self-start sm:self-auto">
            <input type="month" name="bulan" value="{{ request('bulan', $bulanIni->format('Y-m')) }}"
                   class="text-xs border-[#E5E7EB] bg-white rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
            <button type="submit"
                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition shadow-sm inline-flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Unduh Laporan PDF</span>
            </button>
        </form>
    </div>

    {{-- Highlight Perbandingan Bulan Ini vs Bulan Lalu --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Status Evaluasi --}}
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Status Performa</div>
            <div class="my-3">
                @php
                    $color = match ($statusIni['status']) {
                        'Naik'                 => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'Tetap'                => 'bg-sky-50 text-sky-700 border-sky-200',
                        'Turun'                => 'bg-rose-50 text-rose-700 border-rose-200',
                        'Jarang Latihan'       => 'bg-amber-50 text-amber-700 border-amber-200',
                        default                => 'bg-gray-50 text-[#6B7280] border-gray-200',
                    };
                @endphp
                <span class="inline-block px-3 py-1.5 rounded-xl border text-sm font-bold {{ $color }}">
                    {{ $statusIni['status'] }}
                </span>
            </div>
            <div class="text-xs text-[#6B7280]">
                Periode: <strong class="text-[#1F2937]">{{ $bulanIni->translatedFormat('F Y') }}</strong>
            </div>
        </div>

        {{-- Skor Rata-rata --}}
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Rata-rata Skor Bulan Ini</div>
            <div class="my-2 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-[#1F2937]">
                    {{ $statusIni['rata_ini'] !== null ? number_format($statusIni['rata_ini'], 1) : '-' }}
                </span>
                @if ($statusIni['selisih'] !== null)
                    <span class="text-xs font-bold {{ $statusIni['selisih'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $statusIni['selisih'] >= 0 ? '+' : '' }}{{ number_format($statusIni['selisih'], 1) }} poin
                    </span>
                @endif
            </div>
            <div class="text-xs text-[#6B7280]">
                Bulan lalu: {{ $statusIni['rata_lalu'] !== null ? number_format($statusIni['rata_lalu'], 1) : '-' }}
            </div>
        </div>

        {{-- Sesi Valid --}}
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Sesi Latihan Valid</div>
            <div class="my-2">
                <span class="text-3xl font-extrabold text-[#1F2937]">
                    {{ $statusIni['jumlah_sesi'] }}
                </span>
                <span class="text-xs text-[#6B7280] font-normal"> / target min. 4</span>
            </div>
            <div class="text-xs text-[#6B7280]">
                Total semua sesi valid: {{ $totalSesiValid }}
            </div>
        </div>

        {{-- Volume Anak Panah --}}
        <div class="bg-white p-5 rounded-2xl border border-[#E5E7EB] shadow-sm flex flex-col justify-between">
            <div class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Total Volume Panah</div>
            <div class="my-2">
                <span class="text-3xl font-extrabold text-primary-600">
                    {{ $totalAnakPanahBulanIni }}
                </span>
                <span class="text-xs text-[#6B7280] font-normal"> panah</span>
            </div>
            <div class="text-xs text-[#6B7280]">
                Tercatat pada bulan {{ $bulanIni->translatedFormat('F') }}
            </div>
        </div>
    </div>

    {{-- Rekomendasi Pelatih --}}
    @if (!empty($statusIni['saran']))
        <div class="bg-primary-50/70 border border-primary-200 rounded-2xl p-5 flex items-start gap-3.5">
            <span class="text-2xl flex-shrink-0">💡</span>
            <div>
                <h2 class="text-sm font-bold text-primary-900">Rekomendasi Latihan Kamu</h2>
                <p class="text-xs sm:text-sm text-[#1F2937] mt-1 leading-relaxed">
                    {{ $statusIni['saran'] }}
                </p>
            </div>
        </div>
    @endif

    {{-- Grafik Perkembangan Skor (Chart.js: Garis utama #7C3AED, Fill rgba(124,58,237,0.1), Pembanding #A78BFA putus-putus) --}}
    @if ($riwayatValid->isNotEmpty())
        <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Grafik Tren Skor Tembakan</h2>
                    <p class="text-xs text-[#6B7280] mt-0.5">Perbandingan skor sesi valid terhadap rata-rata bulan lalu</p>
                </div>
            </div>
            <div class="h-64 w-full">
                <canvas id="progresChart"></canvas>
            </div>
        </div>
    @endif

    {{-- Log Sesi Tembakan Valid --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-[#E5E7EB] flex items-center justify-between">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">
                Log Skor Sesi Tembakan Valid
            </h2>
            <span class="text-xs text-[#6B7280] font-semibold">{{ $riwayatValid->count() }} Sesi Valid</span>
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
                        <th class="px-6 py-3.5">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse ($riwayatValid as $skor)
                        <tr class="hover:bg-primary-50/30 transition">
                            <td class="px-6 py-4 font-semibold text-[#1F2937] text-xs">
                                {{ $skor->tanggal->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 py-4 text-xs font-semibold text-[#1F2937]">
                                Sesi ke-{{ $skor->sesi_ke }}
                            </td>
                            <td class="px-4 py-4 text-xs font-bold text-[#1F2937]">
                                {{ $skor->jarak_meter }}m
                            </td>
                            <td class="px-4 py-4 text-xs font-mono font-semibold text-[#6B7280]">
                                {{ $skor->qty_panah }} panah
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-extrabold text-primary-600 text-base">
                                {{ $skor->total_skor }} poin
                            </td>
                            <td class="px-6 py-4 text-xs text-[#6B7280] max-w-xs truncate">
                                {{ $skor->catatan ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-[#6B7280] text-xs">
                                Belum ada sesi tembakan valid. Mulai input skor latihanmu sekarang!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($riwayatValid->isNotEmpty())
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('progresChart');
    if (!ctx) return;

    const labels = @json($riwayatValid->map(fn ($s) => $s->tanggal->format('d M') . ' (S' . $s->sesi_ke . ')'));
    const scores = @json($riwayatValid->pluck('total_skor'));
    const avgLalu = @json($statusIni['rata_lalu']);

    const datasets = [
        {
            label: 'Skor Sesi Valid',
            data: scores,
            borderColor: '#7C3AED',
            backgroundColor: 'rgba(124,58,237,0.1)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#7C3AED',
            pointRadius: 4,
        }
    ];

    if (avgLalu !== null) {
        datasets.push({
            label: 'Rata-rata Bulan Lalu',
            data: labels.map(() => avgLalu),
            borderColor: '#A78BFA',
            borderWidth: 2,
            borderDash: [6, 6],
            fill: false,
            pointRadius: 0,
        });
    }

    new Chart(ctx, {
        type: 'line',
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } }
            },
            scales: {
                y: { grid: { color: '#F3F4F6' } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush
@endif
@endsection
