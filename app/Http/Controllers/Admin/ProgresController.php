<?php

namespace App\Http\Controllers\Admin;

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

    public function index(Request $request)
    {
        $bulanIni = Carbon::now()->startOfMonth();

        $anggotaList = Anggota::where('status_keanggotaan', 'aktif')
            ->orderBy('nama')
            ->get()
            ->map(function ($anggota) use ($bulanIni) {
                $status = $this->analisis->statusAnggota($anggota->id, $bulanIni);
                $anggota->analisis = $status;

                return $anggota;
            });

        return view('admin.progres.index', compact('anggotaList', 'bulanIni'));
    }

    public function show(Anggota $anggota)
    {
        $bulanIni = Carbon::now()->startOfMonth();
        $analisis = $this->analisis->statusAnggota($anggota->id, $bulanIni);

        $riwayatSkor = SkorTembakan::where('anggota_id', $anggota->id)
            ->valid()
            ->orderBy('tanggal')
            ->get();

        $qtyShootTerbaru = $anggota->qtyShoot()->latest('tanggal')->take(14)->get();
        $targetLatihan = $anggota->targetLatihan()->latest()->get();

        return view('admin.progres.show', compact('anggota', 'analisis', 'riwayatSkor', 'qtyShootTerbaru', 'targetLatihan', 'bulanIni'));
    }

    public function exportPdf(Request $request, Anggota $anggota)
    {
        Gate::authorize('downloadLaporan', $anggota);

        $bulanStr = $request->get('bulan', now()->format('Y-m'));
        $bulanIni = Carbon::createFromFormat('Y-m', $bulanStr)->startOfMonth();

        $analisis = $this->analisis->statusAnggota($anggota->id, $bulanIni);

        $riwayatSkor = SkorTembakan::where('anggota_id', $anggota->id)
            ->valid()
            ->bulan($bulanIni)
            ->orderBy('tanggal')
            ->orderBy('sesi_ke')
            ->get();

        $totalVolumePanah = (int) $anggota->qtyShoot()
            ->bulan($bulanIni)
            ->sum('jumlah_anak_panah');

        $riwayatKehadiran = $anggota->presensi()
            ->with('jadwalLatihan')
            ->whereHas('jadwalLatihan', function ($q) use ($bulanIni) {
                $q->whereYear('tanggal', $bulanIni->year)
                    ->whereMonth('tanggal', $bulanIni->month);
            })
            ->get();

        $riwayatLomba = $anggota->hasilLomba()
            ->with('kejuaraan')
            ->get();

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

        $namaBersih = preg_replace('/[^A-Za-z0-9_]/', '', str_replace(' ', '_', $anggota->nama));
        $namaBulan = $bulanIni->translatedFormat('F');
        $tahun = $bulanIni->format('Y');

        return $pdf->download("Laporan_{$namaBersih}_{$namaBulan}_{$tahun}.pdf");
    }
}
