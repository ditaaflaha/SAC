<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\SkorTembakan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $anggota = auth()->user()->anggota;
        $jarak = $request->get('jarak', 18);
        $bulanStr = $request->get('bulan', now()->format('Y-m'));
        $bulan = Carbon::createFromFormat('Y-m', $bulanStr)->startOfMonth();

        $rankings = SkorTembakan::select(
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
            ->get();

        $jarakOptions = [5, 10, 18, 30, 50, 70];

        // Cari posisi saya
        $posisiSaya = $rankings->search(fn ($r) => $r->anggota_id === $anggota->id);
        $peringkatSaya = $posisiSaya !== false ? $posisiSaya + 1 : null;

        return view('anggota.leaderboard.index', compact('rankings', 'jarak', 'bulanStr', 'jarakOptions', 'anggota', 'peringkatSaya'));
    }
}
