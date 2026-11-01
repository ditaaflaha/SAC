<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\HasilLomba;
use App\Models\Kejuaraan;
use Illuminate\Http\Request;

class HasilLombaController extends Controller
{
    public function index()
    {
        $kejuaraanList = Kejuaraan::with(['hasilLomba.anggota'])
            ->orderByDesc('tanggal')
            ->get();

        $anggotaAktif = Anggota::where('status_keanggotaan', 'aktif')->orderBy('nama')->get();

        $totalMedali = [
            'emas' => HasilLomba::whereRaw('LOWER(medali) = ?', ['emas'])->count(),
            'perak' => HasilLomba::whereRaw('LOWER(medali) = ?', ['perak'])->count(),
            'perunggu' => HasilLomba::whereRaw('LOWER(medali) = ?', ['perunggu'])->count(),
        ];

        return view('admin.lomba.index', compact('kejuaraanList', 'anggotaAktif', 'totalMedali'));
    }

    public function storeKejuaraan(Request $request)
    {
        $validated = $request->validate([
            'nama_kejuaraan' => 'required|string|max:150',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:150',
            'tingkat' => 'required|in:lokal,provinsi,nasional',
            'link_ianseo' => 'nullable|url',
        ]);

        Kejuaraan::create($validated);

        return back()->with('success', 'Kejuaraan baru berhasil ditambahkan!');
    }

    public function updateKejuaraan(Request $request, Kejuaraan $kejuaraan)
    {
        $validated = $request->validate([
            'nama_kejuaraan' => 'required|string|max:150',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string|max:150',
            'tingkat' => 'required|in:lokal,provinsi,nasional',
            'link_ianseo' => 'nullable|url',
        ]);

        $kejuaraan->update($validated);

        return back()->with('success', "Data kejuaraan '{$kejuaraan->nama_kejuaraan}' berhasil diperbarui!");
    }

    public function destroyKejuaraan(Kejuaraan $kejuaraan)
    {
        $nama = $kejuaraan->nama_kejuaraan;
        $kejuaraan->delete();

        return back()->with('success', "Kejuaraan '{$nama}' beserta daftar hasilnya berhasil dihapus.");
    }

    public function storeHasil(Request $request)
    {
        $validated = $request->validate([
            'kejuaraan_id' => 'nullable|exists:kejuaraan,id',
            'nama_kejuaraan_custom' => 'nullable|string|max:150',
            'anggota_id' => 'required|exists:anggota,id',
            'nama_tim' => 'required|string|max:100',
            'kategori' => 'required|string|max:100',
            'jarak_meter' => 'required|integer|min:1',
            'skor' => 'required|integer|min:0',
            'peringkat' => 'required|integer|min:1',
            'medali' => 'nullable|string|max:50',
        ]);

        if (! empty($validated['nama_kejuaraan_custom'])) {
            if (! empty($validated['kejuaraan_id']) && $request->boolean('rename_kejuaraan')) {
                $kejuaraan = Kejuaraan::findOrFail($validated['kejuaraan_id']);
                $kejuaraan->update(['nama_kejuaraan' => $validated['nama_kejuaraan_custom']]);
            } else {
                $kejuaraan = Kejuaraan::firstOrCreate(
                    ['nama_kejuaraan' => $validated['nama_kejuaraan_custom']],
                    ['tanggal' => now()->toDateString(), 'lokasi' => 'Batam', 'tingkat' => 'lokal']
                );
                $validated['kejuaraan_id'] = $kejuaraan->id;
            }
        }

        unset($validated['nama_kejuaraan_custom']);

        HasilLomba::create($validated);

        return back()->with('success', 'Hasil lomba anggota berhasil dicatat! 🥇');
    }

    public function updateHasil(Request $request, HasilLomba $hasil)
    {
        $validated = $request->validate([
            'kejuaraan_id' => 'nullable|exists:kejuaraan,id',
            'nama_kejuaraan_custom' => 'nullable|string|max:150',
            'anggota_id' => 'required|exists:anggota,id',
            'nama_tim' => 'required|string|max:100',
            'kategori' => 'required|string|max:100',
            'jarak_meter' => 'required|integer|min:1',
            'skor' => 'required|integer|min:0',
            'peringkat' => 'required|integer|min:1',
            'medali' => 'nullable|string|max:50',
        ]);

        if (! empty($validated['nama_kejuaraan_custom'])) {
            if (! empty($validated['kejuaraan_id']) && $request->boolean('rename_kejuaraan')) {
                $kejuaraan = Kejuaraan::findOrFail($validated['kejuaraan_id']);
                $kejuaraan->update(['nama_kejuaraan' => $validated['nama_kejuaraan_custom']]);
            } else {
                $kejuaraan = Kejuaraan::firstOrCreate(
                    ['nama_kejuaraan' => $validated['nama_kejuaraan_custom']],
                    ['tanggal' => now()->toDateString(), 'lokasi' => 'Batam', 'tingkat' => 'lokal']
                );
                $validated['kejuaraan_id'] = $kejuaraan->id;
            }
        }

        unset($validated['nama_kejuaraan_custom']);

        $hasil->update($validated);

        return back()->with('success', 'Data hasil lomba atlet berhasil diperbarui!');
    }

    public function destroyHasil(HasilLomba $hasil)
    {
        $hasil->delete();

        return back()->with('success', 'Hasil lomba berhasil dihapus.');
    }
}
