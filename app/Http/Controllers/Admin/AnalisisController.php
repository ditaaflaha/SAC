<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peringatan;
use App\Services\AnalisisService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AnalisisController extends Controller
{
    public function __construct(private AnalisisService $analisis) {}

    public function index(Request $request)
    {
        $bulanStr = $request->get('bulan', now()->format('Y-m'));
        $bulan = Carbon::createFromFormat('Y-m', $bulanStr)->startOfMonth();

        $rekapKomunitas = $this->analisis->rekapKomunitas($bulan);

        $daftarPeringatan = Peringatan::with('anggota')
            ->where('bulan', $bulan->toDateString())
            ->orderBy('sudah_ditindaklanjuti')
            ->latest()
            ->get();

        return view('admin.analisis.index', compact('rekapKomunitas', 'daftarPeringatan', 'bulan', 'bulanStr'));
    }

    public function tindaklanjuti(Peringatan $peringatan)
    {
        $peringatan->update([
            'sudah_ditindaklanjuti' => ! $peringatan->sudah_ditindaklanjuti,
        ]);

        $status = $peringatan->sudah_ditindaklanjuti ? 'selesai ditindaklanjuti' : 'ditandai belum ditindaklanjuti';

        return back()->with('success', "Peringatan untuk {$peringatan->anggota->nama} berhasil {$status}.");
    }

    public function destroy(Peringatan $peringatan)
    {
        $nama = $peringatan->anggota->nama;
        $peringatan->delete();

        return back()->with('success', "Peringatan/rekomendasi untuk {$nama} berhasil dihapus.");
    }
}
