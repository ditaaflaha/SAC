<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class IuranController extends Controller
{
    public function index()
    {
        $anggota = auth()->user()->anggota;
        $bulanIni = Carbon::now()->startOfMonth();

        $tarifIuran = Tarif::where('jenis', 'iuran_bulanan')->first();
        $isFreePeriod = $anggota->dalamPeriodeFree();

        $pembayaranBulanIni = Pembayaran::where('anggota_id', $anggota->id)
            ->bulan($bulanIni)
            ->get();

        $riwayatPembayaran = Pembayaran::with('tarif')
            ->where('anggota_id', $anggota->id)
            ->latest('tanggal_bayar')
            ->paginate(10);

        return view('anggota.iuran.index', compact(
            'anggota',
            'tarifIuran',
            'isFreePeriod',
            'pembayaranBulanIni',
            'riwayatPembayaran',
            'bulanIni'
        ));
    }

    public function bayar(Request $request)
    {
        $anggota = auth()->user()->anggota;

        $validated = $request->validate([
            'tanggal_bayar' => 'required|date|before_or_equal:today',
            'periode' => 'required|date',
            'cicilan_ke' => 'nullable|in:1,2',
            'jumlah' => 'required|integer|min:10000',
            'bukti_transfer' => 'required|image|max:4096',
        ]);

        $tarifIuran = Tarif::where('jenis', 'iuran_bulanan')->first();

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        Pembayaran::create([
            'anggota_id' => $anggota->id,
            'tarif_id' => $tarifIuran?->id ?? 1,
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'periode' => $validated['periode'],
            'cicilan_ke' => $validated['cicilan_ke'] ?? null,
            'jumlah' => $validated['jumlah'],
            'bukti_transfer' => $path,
            'status' => 'menunggu',
        ]);

        return back()->with('success', 'Bukti transfer berhasil diunggah! Admin akan segera memverifikasi pembayaranmu. Jazakallahu khair! 🏹');
    }
}
