<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\JadwalLatihan;
use App\Models\Presensi;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $anggota = auth()->user()->anggota;

        $jadwalMendatang = JadwalLatihan::where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->get();

        $jadwalLampau = JadwalLatihan::where('tanggal', '<', now()->toDateString())
            ->orderByDesc('tanggal')
            ->paginate(10);

        $presensiSaya = Presensi::where('anggota_id', $anggota->id)
            ->pluck('waktu_hadir', 'jadwal_id')
            ->all();

        return view('anggota.jadwal.index', compact('jadwalMendatang', 'jadwalLampau', 'presensiSaya'));
    }

    public function presensi(Request $request, JadwalLatihan $jadwal)
    {
        $anggota = auth()->user()->anggota;

        // Validasi hanya bisa presensi pada hari H
        if (! $jadwal->isHariIni()) {
            return back()->with('error', 'Presensi mandiri hanya dapat dilakukan pada tanggal jadwal latihan berlangsung.');
        }

        $presensi = Presensi::firstOrCreate(
            [
                'anggota_id' => $anggota->id,
                'jadwal_id' => $jadwal->id,
            ],
            [
                'waktu_hadir' => now(),
            ]
        );

        return back()->with('success', 'Alhamdulillah! Presensi kamu berhasil dicatat pada '.now()->format('H:i').' WIB 🏹');
    }
}
