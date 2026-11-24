<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\SkorTembakan;
use App\Services\AnalisisService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class ProgresController extends Controller
{
    public function __construct(private AnalisisService $analisis) {}

    public function index()
    {
        $anggota = auth()->user()->anggota;
        $bulanIni = Carbon::now()->startOfMonth();
        $bulanLalu = $bulanIni->copy()->subMonth();

        $statusIni = $this->analisis->statusAnggota($anggota->id, $bulanIni);
        $statusLalu = $this->analisis->statusAnggota($anggota->id, $bulanLalu);

        // Riwayat skor tembakan valid untuk grafik perkembangan
        $riwayatValid = SkorTembakan::where('anggota_id', $anggota->id)
            ->valid()
            ->orderBy('tanggal')
            ->get();

        $totalAnakPanahBulanIni = $anggota->qtyShoot()
            ->bulan($bulanIni)
            ->sum('jumlah_anak_panah');

        $totalSesiValid = $riwayatValid->count();

        return view('anggota.progres.index', compact(
            'anggota',
            'statusIni',
            'statusLalu',
            'riwayatValid',
            'totalAnakPanahBulanIni',
            'totalSesiValid',
            'bulanIni'
        ));
    }

    public function exportPdf(Request $request, ?Anggota $anggota = null)
    {
        $targetAnggota = $anggota ?? auth()->user()->anggota;

        Gate::authorize('downloadLaporan', $targetAnggota);

        $bulanStr = $request->get('bulan', now()->format('Y-m'));
        $bulanIni = Carbon::createFromFormat('Y-m', $bulanStr)->startOfMonth();

        $analisis = $this->analisis->statusAnggota($targetAnggota->id, $bulanIni);

        $riwayatSkor = SkorTembakan::where('anggota_id', $targetAnggota->id)
            ->valid()
            ->bulan($bulanIni)
            ->orderBy('tanggal')
            ->orderBy('sesi_ke')
            ->get();

        $totalVolumePanah = (int) $targetAnggota->qtyShoot()
            ->bulan($bulanIni)
            ->sum('jumlah_anak_panah');

        $riwayatKehadiran = $targetAnggota->presensi()
            ->with('jadwalLatihan')
            ->whereHas('jadwalLatihan', function ($q) use ($bulanIni) {
                $q->whereYear('tanggal', $bulanIni->year)
                    ->whereMonth('tanggal', $bulanIni->month);
            })
            ->get();

        $riwayatLomba = $targetAnggota->hasilLomba()
            ->with('kejuaraan')
            ->get();

        $anggota = $targetAnggota;

        $pdf = Pdf::loadView('admin.progres.pdf', compact(
            'anggota',
            'analisis',
            'riwayatSkor',
            'totalVolumePanah',
            'riwayatKehadiran',
            'riwayatLomba',
            'bulanIni'
        ))
            ->setOptions(['isRemoteEnabled' => true])
            ->setPaper('a4', 'portrait');

        $namaBersih = preg_replace('/[^A-Za-z0-9_]/', '', str_replace(' ', '_', $targetAnggota->nama));
        $namaBulan = $bulanIni->translatedFormat('F');
        $tahun = $bulanIni->format('Y');

        return $pdf->download("Laporan_{$namaBersih}_{$namaBulan}_{$tahun}.pdf");
    }

    public function ktaPdf()
    {
        $anggota = auth()->user()->anggota;

        $pdf = Pdf::loadView('anggota.kta-pdf', compact('anggota'))
            ->setOptions(['isRemoteEnabled' => true])
            ->setPaper('a6', 'landscape');

        return $pdf->download("KTA-SAC-{$anggota->id}.pdf");
    }
}
