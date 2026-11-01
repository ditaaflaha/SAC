<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\SkorTembakan;
use App\Services\AnalisisService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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

    public function exportPdf(Anggota $anggota)
    {
        $bulanIni = Carbon::now()->startOfMonth();
        $analisis = $this->analisis->statusAnggota($anggota->id, $bulanIni);

        $riwayatSkor = SkorTembakan::where('anggota_id', $anggota->id)
            ->valid()
            ->orderBy('tanggal')
            ->get();

        $pdf = Pdf::loadView('admin.progres.pdf', compact('anggota', 'analisis', 'riwayatSkor', 'bulanIni'));

        $slug = Str::slug($anggota->nama);

        return $pdf->download("rapor-progres-{$slug}.pdf");
    }
}
