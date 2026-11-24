@extends('layouts.admin')

@section('title', 'Dashboard Analisis Perkembangan Komunitas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Dashboard Analisis Komunitas</h1>
            <p class="text-sm text-[#6B7280]">Evaluasi menyeluruh kenaikan/penurunan skor dan tindak lanjut peringatan pelatih.</p>
        </div>

        <form method="GET" action="{{ route('admin.analisis') }}" class="flex items-center gap-2">
            <span class="text-xs font-semibold text-[#6B7280]">Bulan:</span>
            <input type="month" name="bulan" value="{{ $bulanStr }}" onchange="this.form.submit()"
                   class="text-xs border-[#E5E7EB] rounded-xl px-3 py-1.5 focus:ring-primary-500 focus:border-primary-500">
        </form>
    </div>

    {{-- Grafik Perbandingan Rata-rata Skor Bulan Ini vs Bulan Lalu (Chart.js) --}}
    @if ($rekapKomunitas->isNotEmpty())
        <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                <div>
                    <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Grafik Perbandingan Skor Anggota</h2>
                    <p class="text-xs text-[#6B7280] mt-0.5">Rata-rata skor bulan ini (#7C3AED) vs bulan lalu (#A78BFA putus-putus)</p>
                </div>
            </div>
            <div class="h-64 w-full">
                <canvas id="analisisChart"></canvas>
            </div>
        </div>
    @endif

    {{-- Daftar Peringatan yang Membutuhkan Perhatian Pelatih --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">
                    Daftar Peringatan & Evaluasi Pelatih
                </h2>
                <p class="text-xs text-[#6B7280] mt-0.5">Pemanah dengan status Tetap, Turun, atau Jarang Latihan yang perlu intervensi</p>
            </div>
            <span class="px-3 py-1 bg-primary-50 text-primary-700 rounded-full font-bold text-xs">
                {{ $daftarPeringatan->where('sudah_ditindaklanjuti', false)->count() }} Belum Ditindaklanjuti
            </span>
        </div>

        <div class="space-y-3">
            @forelse ($daftarPeringatan as $p)
                @php
                    $pBadge = match ($p->status) {
                        'Turun'          => 'bg-rose-50 text-rose-700',
                        'Tetap'          => 'bg-sky-50 text-sky-700',
                        'Jarang Latihan' => 'bg-amber-50 text-amber-700',
                        default          => 'bg-gray-100 text-[#6B7280]',
                    };
                @endphp
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 rounded-xl border {{ $p->sudah_ditindaklanjuti ? 'bg-[#FAFAFB] border-[#E5E7EB] opacity-70' : 'bg-white border-[#E5E7EB]' }} gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-[#1F2937]">{{ $p->anggota->nama }}</span>
                            <span class="px-2.5 py-0.5 text-[11px] font-bold rounded-full {{ $pBadge }}">
                                {{ $p->status }}
                            </span>
                            @if ($p->sudah_ditindaklanjuti)
                                <span class="px-2 py-0.5 text-[10px] bg-emerald-50 text-emerald-700 rounded-full font-bold">
                                    ✓ Selesai Ditindaklanjuti
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-[#6B7280] leading-relaxed">
                            💡 Rekomendasi: {{ $p->rekomendasi ?: 'Perlu evaluasi langsung oleh pelatih.' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <form method="POST" action="{{ route('admin.analisis.tindaklanjuti', $p) }}">
                            @csrf
                            <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition {{ $p->sudah_ditindaklanjuti ? 'bg-primary-50 text-primary-700 hover:bg-primary-100' : 'bg-primary-600 text-white hover:bg-primary-700 shadow-sm' }}">
                                {{ $p->sudah_ditindaklanjuti ? 'Tandai Belum' : '✓ Tandai Sudah Ditindaklanjuti' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.analisis.destroy', $p) }}" onsubmit="return confirm('Hapus catatan peringatan/rekomendasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold transition">
                                🗑️ Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-xs text-[#6B7280] py-4 text-center">Tidak ada peringatan untuk periode bulan ini.</p>
            @endforelse
        </div>
    </div>

    {{-- Rekap Seluruh Anggota --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-[#E5E7EB]">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">
                Rekap Analisis Komunitas ({{ $bulan->translatedFormat('F Y') }})
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[#6B7280]">
                <thead class="bg-[#FAFAFB] text-xs uppercase font-semibold text-[#6B7280] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-3.5">Nama Pemanah</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Sesi</th>
                        <th class="px-4 py-3.5 text-right">Rata Lalu</th>
                        <th class="px-4 py-3.5 text-right">Rata Ini</th>
                        <th class="px-4 py-3.5 text-center">Selisih</th>
                        <th class="px-6 py-3.5">Rekomendasi Terkirim</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse ($rekapKomunitas as $item)
                        @php
                            $badge = match ($item['status']) {
                                'Naik'                 => 'bg-emerald-50 text-emerald-700',
                                'Tetap'                => 'bg-sky-50 text-sky-700',
                                'Turun'                => 'bg-rose-50 text-rose-700',
                                'Jarang Latihan'       => 'bg-amber-50 text-amber-700',
                                default                => 'bg-gray-100 text-[#6B7280]',
                            };
                        @endphp
                        <tr class="hover:bg-primary-50/30 transition">
                            <td class="px-6 py-3.5">
                                <div class="font-bold text-[#1F2937]">{{ $item['anggota']->nama }}</div>
                                <div class="text-xs text-[#6B7280]">{{ $item['anggota']->jenis_keanggotaan }}</div>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $badge }}">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center font-semibold text-[#1F2937]">
                                {{ $item['jumlah_sesi'] }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-mono text-[#6B7280]">
                                {{ $item['rata_lalu'] !== null ? number_format($item['rata_lalu'], 1) : '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-right font-mono font-bold text-[#1F2937]">
                                {{ $item['rata_ini'] !== null ? number_format($item['rata_ini'], 1) : '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-center font-mono font-bold">
                                @if ($item['selisih'] !== null)
                                    <span class="{{ $item['selisih'] > 0 ? 'text-emerald-600' : ($item['selisih'] < 0 ? 'text-rose-600' : 'text-[#6B7280]') }}">
                                        {{ $item['selisih'] > 0 ? '+' : '' }}{{ number_format($item['selisih'], 1) }}
                                    </span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-xs text-[#6B7280] max-w-sm truncate" title="{{ $item['saran'] }}">
                                {{ $item['saran'] ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-[#6B7280]">
                                Belum ada data anggota aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($rekapKomunitas->isNotEmpty())
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('analisisChart');
    if (!ctx) return;

    const subset = @json($rekapKomunitas->take(12)->values());
    const labels = subset.map(i => i.anggota.nama.split(' ')[0]);
    const dataIni = subset.map(i => i.rata_ini ?? 0);
    const dataLalu = subset.map(i => i.rata_lalu ?? 0);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Rata-rata Bulan Ini',
                    data: dataIni,
                    borderColor: '#7C3AED',
                    backgroundColor: 'rgba(124,58,237,0.1)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#7C3AED',
                    pointRadius: 4,
                },
                {
                    label: 'Rata-rata Bulan Lalu',
                    data: dataLalu,
                    borderColor: '#A78BFA',
                    borderWidth: 2,
                    borderDash: [6, 6],
                    fill: false,
                    tension: 0.35,
                    pointBackgroundColor: '#A78BFA',
                    pointRadius: 3,
                }
            ]
        },
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
