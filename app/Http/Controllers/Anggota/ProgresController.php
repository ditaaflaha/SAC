<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\SkorTembakan;
use App\Services\AnalisisService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

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

    public function ktaPdf()
    {
        $anggota = auth()->user()->anggota;

        $pdf = Pdf::loadView('anggota.kta-pdf', compact('anggota'))
            ->setOptions(['isRemoteEnabled' => true])
            ->setPaper('a6', 'landscape');

        return $pdf->download("KTA-SAC-{$anggota->id}.pdf");
    }
}
