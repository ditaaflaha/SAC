<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Pembayaran;
use App\Models\Tarif;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class IuranController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'semua');
        $bulanStr = $request->get('bulan', now()->format('Y-m'));
        $bulan = Carbon::createFromFormat('Y-m', $bulanStr)->startOfMonth();

        $query = Pembayaran::with(['anggota', 'tarif', 'verifikator'])->latest('tanggal_bayar');

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $pembayaranList = $query->paginate(15)->withQueryString();

        $tarifList = Tarif::orderBy('jenis')->get();
        $anggotaList = Anggota::where('status_keanggotaan', 'aktif')->orderBy('nama')->get();

        $totalMasuk = Pembayaran::lunas()
            ->whereYear('tanggal_bayar', $bulan->year)
            ->whereMonth('tanggal_bayar', $bulan->month)
            ->sum('jumlah');

        $counts = [
            'menunggu' => Pembayaran::where('status', 'menunggu')->count(),
            'lunas' => Pembayaran::where('status', 'lunas')->count(),
            'free' => Pembayaran::where('status', 'free')->count(),
            'ditolak' => Pembayaran::where('status', 'ditolak')->count(),
        ];

        return view('admin.iuran.index', compact(
            'pembayaranList',
            'tarifList',
            'anggotaList',
            'totalMasuk',
            'counts',
            'status',
            'bulanStr',
            'bulan'
        ));
    }

    public function exportPdf(Request $request)
    {
        $bulanStr = $request->get('bulan', now()->format('Y-m'));
        $bulan = Carbon::createFromFormat('Y-m', $bulanStr)->startOfMonth();

        $transaksi = Pembayaran::with(['anggota', 'tarif', 'verifikator'])
            ->whereYear('tanggal_bayar', $bulan->year)
            ->whereMonth('tanggal_bayar', $bulan->month)
            ->orderBy('tanggal_bayar')
            ->get();

        $totalLunas = $transaksi->where('status', 'lunas')->sum('jumlah');

        $pdf = Pdf::loadView('admin.iuran.pdf', compact('transaksi', 'totalLunas', 'bulan', 'bulanStr'));

        return $pdf->download("laporan-kas-sac-{$bulanStr}.pdf");
    }

    public function verifikasi(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'status' => 'required|in:lunas,ditolak',
            'alasan_tolak' => 'nullable|string',
        ]);

        $pembayaran->update([
            'status' => $validated['status'],
            'alasan_tolak' => $validated['alasan_tolak'] ?? null,
            'diverifikasi_oleh' => auth()->id(),
        ]);

        $statusText = $validated['status'] === 'lunas' ? 'disetujui (Lunas)' : 'ditolak';

        return back()->with('success', 'Pembayaran senilai Rp'.number_format($pembayaran->jumlah, 0, ',', '.')." berhasil {$statusText}.");
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'anggota_id' => 'nullable|exists:anggota,id',
            'nama_peserta' => 'nullable|string|max:100',
            'tarif_id' => 'required|exists:tarif,id',
            'tanggal_bayar' => 'required|date',
            'periode' => 'nullable|date',
            'cicilan_ke' => 'nullable|in:1,2',
            'jumlah' => 'required|integer|min:0',
        ]);

        $validated['status'] = 'lunas';
        $validated['diverifikasi_oleh'] = auth()->id();

        Pembayaran::create($validated);

        return back()->with('success', 'Pembayaran manual berhasil dicatat!');
    }

    public function updateTarif(Request $request, Tarif $tarif)
    {
        $validated = $request->validate([
            'nominal' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
        ]);

        $tarif->update($validated);

        return back()->with('success', "Tarif {$tarif->jenis} berhasil diperbarui.");
    }
}
