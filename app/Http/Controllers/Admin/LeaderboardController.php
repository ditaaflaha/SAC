<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalLatihan;
use App\Models\Presensi;
use App\Models\QtyShoot;
use App\Models\SkorTembakan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $kategori = in_array($request->get('kategori'), ['skor', 'rajin', 'hadir'], true)
            ? $request->get('kategori')
            : 'skor';

        $jarak = (int) $request->get('jarak', 18);
        $bulanStr = $request->get('bulan', now()->format('Y-m'));
        $bulan = Carbon::createFromFormat('Y-m', $bulanStr)->startOfMonth();
        $jarakOptions = [5, 10, 18, 30, 50, 70];

        $totalJadwalBulanIni = JadwalLatihan::whereYear('tanggal', $bulan->year)
            ->whereMonth('tanggal', $bulan->month)
            ->count();

        $rankings = match ($kategori) {
            'rajin' => QtyShoot::select(
                'anggota_id',
                DB::raw('COUNT(DISTINCT tanggal) as total_hari'),
                DB::raw('SUM(jumlah_anak_panah) as total_panah')
            )
                ->bulan($bulan)
                ->groupBy('anggota_id')
                ->orderByDesc('total_panah')
                ->with('anggota')
                ->get(),

            'hadir' => Presensi::select(
                'presensi.anggota_id',
                DB::raw('COUNT(*) as total_hadir')
            )
                ->join('jadwal_latihan', 'jadwal_latihan.id', '=', 'presensi.jadwal_id')
                ->whereYear('jadwal_latihan.tanggal', $bulan->year)
                ->whereMonth('jadwal_latihan.tanggal', $bulan->month)
                ->groupBy('presensi.anggota_id')
                ->orderByDesc('total_hadir')
                ->with('anggota')
                ->get(),

            default => SkorTembakan::select(
                'anggota_id',
                DB::raw('COUNT(*) as total_sesi'),
                DB::raw('ROUND(AVG(total_skor), 2) as rata_skor'),
                DB::raw('MAX(total_skor) as skor_tertinggi')
            )
                ->where('status_validasi', 'valid')
                ->where('jarak_meter', $jarak)
                ->bulan($bulan)
                ->groupBy('anggota_id')
                ->orderByDesc('rata_skor')
                ->with('anggota')
                ->get(),
        };

        return view('admin.leaderboard.index', compact(
            'rankings',
            'kategori',
            'jarak',
            'bulanStr',
            'bulan',
            'jarakOptions',
            'totalJadwalBulanIni'
        ));
    }
}
