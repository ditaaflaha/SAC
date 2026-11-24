<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
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

        return view('anggota.dashboard', compact('anggota', 'status', 'pengumuman'));
    }
}
