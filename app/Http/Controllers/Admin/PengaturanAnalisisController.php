<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanAnalisis;
use Illuminate\Http\Request;

class PengaturanAnalisisController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanAnalisis::aktif();

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'batas_poin' => 'required|integer|min:1|max:50',
            'min_latihan_bulanan' => 'required|integer|min:1|max:31',
            'anak_panah_per_sesi' => 'required|integer|min:1|max:200',
            'saran_naik' => 'required|string',
            'saran_tetap' => 'required|string',
            'saran_turun' => 'required|string',
            'saran_jarang' => 'required|string',
        ]);

        $pengaturan = PengaturanAnalisis::aktif();
        $validated['user_id'] = auth()->id();
        $pengaturan->update($validated);

        return back()->with('success', 'Pengaturan parameter analisis & rekomendasi berhasil disimpan!');
    }
}
