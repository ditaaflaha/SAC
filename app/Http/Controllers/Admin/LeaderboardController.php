<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkorTembakan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $jarak = $request->get('jarak', 18);
        $bulanStr = $request->get('bulan', now()->format('Y-m'));
        $bulan = Carbon::createFromFormat('Y-m', $bulanStr)->startOfMonth();

        // Ambil ranking per anggota berdasarkan skor rata-rata pada jarak & bulan tertentu
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

        return view('admin.leaderboard.index', compact('rankings', 'jarak', 'bulanStr', 'jarakOptions'));
    }
}
