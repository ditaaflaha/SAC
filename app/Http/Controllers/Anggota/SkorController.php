<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\QtyShoot;
use App\Models\SkorTembakan;
use Illuminate\Http\Request;

class SkorController extends Controller
{
    public function index()
    {
        $anggota = auth()->user()->anggota;

        $riwayatSkor = SkorTembakan::where('anggota_id', $anggota->id)
            ->latest('tanggal')
            ->paginate(12, ['*'], 'skor_page');

        $riwayatQty = QtyShoot::where('anggota_id', $anggota->id)
            ->latest('tanggal')
            ->take(10)
            ->get();

        $qtyHariIni = QtyShoot::where('anggota_id', $anggota->id)
            ->where('tanggal', now()->toDateString())
            ->first();

        return view('anggota.skor.index', compact('riwayatSkor', 'riwayatQty', 'qtyHariIni'));
    }

    public function store(Request $request)
    {
        $anggota = auth()->user()->anggota;

        $validated = $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'sesi_ke' => 'required|integer|min:1|max:10',
            'jarak_meter' => 'required|integer|min:5|max:100',
            'jumlah_anak_panah' => 'required|integer|min:1|max:500',
            'total_skor' => 'required|integer|min:0',
            'catatan' => 'nullable|string|max:255',
        ]);

        $maxPossible = $validated['jumlah_anak_panah'] * 10;
        if ($validated['total_skor'] > $maxPossible) {
            return back()->withInput()->with('error', "Total skor ({$validated['total_skor']}) melebihi skor maksimal dari {$validated['jumlah_anak_panah']} anak panah ({$maxPossible} poin).");
        }

        $validated['anggota_id'] = $anggota->id;
        $validated['status_validasi'] = 'menunggu';

        SkorTembakan::create($validated);

        return back()->with('success', 'Skor tembakan berhasil dikirim! Menunggu validasi admin/pelatih 🎯');
    }

    public function storeQty(Request $request)
    {
        $anggota = auth()->user()->anggota;

        $validated = $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'jumlah_anak_panah' => 'required|integer|min:1|max:500',
        ]);

        QtyShoot::updateOrCreate(
            [
                'anggota_id' => $anggota->id,
                'tanggal' => $validated['tanggal'],
            ],
            [
                'jumlah_anak_panah' => $validated['jumlah_anak_panah'],
            ]
        );

        return back()->with('success', "Qty Shoot harian ({$validated['jumlah_anak_panah']} anak panah) berhasil dicatat!");
    }
}
