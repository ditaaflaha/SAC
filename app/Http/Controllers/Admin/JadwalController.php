<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\JadwalLatihan;
use App\Models\Presensi;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwalList = JadwalLatihan::withCount('presensi')
            ->orderByDesc('tanggal')
            ->paginate(12);

        return view('admin.jadwal.index', compact('jadwalList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'lokasi' => 'required|string|max:150',
            'jenis' => 'required|in:wajib,mandiri',
            'keterangan' => 'nullable|string',
        ]);

        JadwalLatihan::create($validated);

        return back()->with('success', 'Jadwal latihan baru berhasil ditambahkan! 🏹');
    }

    public function destroy(JadwalLatihan $jadwal)
    {
        $jadwal->delete();

        return back()->with('success', 'Jadwal latihan berhasil dihapus.');
    }

    public function presensi(JadwalLatihan $jadwal)
    {
        $jadwal->load('presensi.anggota');
        $anggotaAktif = Anggota::where('status_keanggotaan', 'aktif')->orderBy('nama')->get();

        $hadirIds = $jadwal->presensi->pluck('anggota_id')->flip()->all();

        return view('admin.jadwal.presensi', compact('jadwal', 'anggotaAktif', 'hadirIds'));
    }

    public function presensiToggle(Request $request, JadwalLatihan $jadwal)
    {
        $request->validate([
            'anggota_id' => 'required|exists:anggota,id',
        ]);

        $anggotaId = $request->anggota_id;
        $presensi = Presensi::where('jadwal_id', $jadwal->id)->where('anggota_id', $anggotaId)->first();

        if ($presensi) {
            $presensi->delete();
            $status = 'dibatalkan';
        } else {
            Presensi::create([
                'jadwal_id' => $jadwal->id,
                'anggota_id' => $anggotaId,
                'waktu_hadir' => now(),
            ]);
            $status = 'dicatat hadir';
        }

        return back()->with('success', "Status presensi anggota berhasil {$status}.");
    }
}
