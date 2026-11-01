<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Peringatan;
use App\Models\TargetLatihan;
use App\Services\AnalisisService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TargetController extends Controller
{
    public function __construct(private AnalisisService $analisis) {}

    public function index()
    {
        $anggota = auth()->user()->anggota;
        $bulanIni = Carbon::now()->startOfMonth();

        $status = $this->analisis->statusAnggota($anggota->id, $bulanIni);

        $targetList = TargetLatihan::where('anggota_id', $anggota->id)
            ->latest()
            ->get();

        $rekomendasiPelatih = Peringatan::where('anggota_id', $anggota->id)
            ->latest('bulan')
            ->first();

        $sembunyikanRekomendasi = session('hide_rekomendasi_'.$anggota->id, false);

        return view('anggota.target.index', compact('anggota', 'status', 'targetList', 'rekomendasiPelatih', 'sembunyikanRekomendasi'));
    }

    public function store(Request $request)
    {
        $anggota = auth()->user()->anggota;

        $validated = $request->validate([
            'jenis_target' => 'required|in:rata_rata_skor,qty_shoot,kehadiran',
            'nilai_target' => 'required|integer|min:1',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
        ]);

        $validated['anggota_id'] = $anggota->id;
        $validated['status'] = 'berjalan';

        TargetLatihan::create($validated);

        return back()->with('success', 'Target latihan barumu berhasil disimpan! Bismillah, semoga tercapai! 🎯');
    }

    public function destroy(TargetLatihan $target)
    {
        $anggota = auth()->user()->anggota;
        abort_if($target->anggota_id !== $anggota->id, 403);

        $target->delete();

        return back()->with('success', 'Target latihan berhasil dihapus.');
    }

    public function destroyRekomendasi()
    {
        $anggota = auth()->user()->anggota;

        Peringatan::where('anggota_id', $anggota->id)->delete();
        session(['hide_rekomendasi_'.$anggota->id => true]);

        return back()->with('success', 'Catatan rekomendasi berhasil dihapus.');
    }
}
