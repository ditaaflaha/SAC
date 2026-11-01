<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Pembayaran;
use App\Models\Pengumuman;
use App\Services\AnalisisService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function __construct(private AnalisisService $analisis) {}

    public function index()
    {
        $anggota = auth()->user()->anggota;

        // Jika user admin yang akses halaman ini, arahkan ke dashboard admin
        if (! $anggota) {
            return redirect()->route('admin.dashboard');
        }

        $bulanIni = Carbon::now()->startOfMonth();

        // Analisis bulan ini
        $status = $this->analisis->statusAnggota($anggota->id, $bulanIni);

        // Pengumuman terbaru (internal + publik)
        $pengumuman = Pengumuman::orderByDesc('tanggal')->take(3)->get();

        // Status iuran bulan ini (diambil sederhana — detail di IuranService Sprint 5)
        $statusIuran = $this->getStatusIuranSingkat($anggota->id, $bulanIni);

        return view('anggota.dashboard', compact('anggota', 'status', 'pengumuman', 'statusIuran'));
    }

    /**
     * Cek status iuran sederhana untuk ditampilkan di dashboard.
     * Logika penuh ada di IuranService (Sprint 5).
     */
    private function getStatusIuranSingkat(int $anggotaId, Carbon $bulan): string
    {
        $pembayaran = Pembayaran::where('anggota_id', $anggotaId)
            ->bulan($bulan)
            ->get();

        if ($pembayaran->where('status', 'free')->isNotEmpty()) {
            return 'FREE';
        }

        $totalLunas = $pembayaran->where('status', 'lunas')->sum('jumlah');

        if ($totalLunas >= 40000) {
            return 'Lunas';
        }

        if ($totalLunas > 0) {
            return 'C1'; // Cicilan pertama saja
        }

        // Cek apakah dalam periode 2 bulan gratis
        $anggota = Anggota::find($anggotaId);
        if ($anggota?->dalamPeriodeFree()) {
            return 'FREE';
        }

        return 'Belum';
    }
}
