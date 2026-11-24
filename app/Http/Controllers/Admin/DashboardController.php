<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Peringatan;
use App\Services\AnalisisService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __construct(private AnalisisService $analisis) {}

    public function index()
    {
        $bulanIni = Carbon::now()->startOfMonth();

        // Ringkasan kartu statistik
        $totalAnggotaAktif = Anggota::where('status_keanggotaan', 'aktif')->count();

        // Anggota yang perlu perhatian bulan ini
        $perluPerhatian = Peringatan::where('bulan', $bulanIni->toDateString())
            ->where('sudah_ditindaklanjuti', false)
            ->count();

        // Anggota baru yang belum diproses (status menunggu)
        $pendaftarBaru = Anggota::where('status_keanggotaan', 'menunggu')->count();

        // Rekap status komunitas bulan ini
        $rekapKomunitas = $this->analisis->rekapKomunitas($bulanIni);

        return view('admin.dashboard', compact(
            'totalAnggotaAktif',
            'perluPerhatian',
            'pendaftarBaru',
            'rekapKomunitas',
            'bulanIni',
        ));
    }
}
