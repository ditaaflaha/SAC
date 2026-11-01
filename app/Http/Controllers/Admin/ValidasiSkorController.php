<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkorTembakan;
use Illuminate\Http\Request;

class ValidasiSkorController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'menunggu');

        $query = SkorTembakan::with(['anggota', 'validator'])->latest('tanggal');

        if ($status !== 'semua') {
            $query->where('status_validasi', $status);
        }

        $skorList = $query->paginate(15)->withQueryString();

        $counts = [
            'menunggu' => SkorTembakan::where('status_validasi', 'menunggu')->count(),
            'valid' => SkorTembakan::where('status_validasi', 'valid')->count(),
            'ditolak' => SkorTembakan::where('status_validasi', 'ditolak')->count(),
            'semua' => SkorTembakan::count(),
        ];

        return view('admin.validasi-skor.index', compact('skorList', 'counts', 'status'));
    }

    public function validasi(Request $request, SkorTembakan $skor)
    {
        $validated = $request->validate([
            'status_validasi' => 'required|in:valid,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $skor->update([
            'status_validasi' => $validated['status_validasi'],
            'divalidasi_oleh' => auth()->id(),
            'catatan' => $validated['catatan'] ?? $skor->catatan,
        ]);

        $statusText = $validated['status_validasi'] === 'valid' ? 'disetujui (valid)' : 'ditolak';

        return back()->with('success', "Skor tembakan atas nama {$skor->anggota->nama} berhasil {$statusText}!");
    }
}
