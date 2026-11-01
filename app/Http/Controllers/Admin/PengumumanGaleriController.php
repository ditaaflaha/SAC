<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanGaleriController extends Controller
{
    public function index()
    {
        $pengumumanList = Pengumuman::with('penulis')->latest('tanggal')->paginate(8, ['*'], 'pengumuman_page');
        $galeriList = Galeri::with('pengunggah')->latest('tanggal')->paginate(12, ['*'], 'galeri_page');

        return view('admin.pengumuman.index', compact('pengumumanList', 'galeriList'));
    }

    public function storePengumuman(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:150',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
            'publik' => 'nullable|boolean',
        ]);

        Pengumuman::create([
            'user_id' => auth()->id(),
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'tanggal' => $validated['tanggal'],
            'publik' => $request->boolean('publik'),
        ]);

        return back()->with('success', 'Pengumuman baru berhasil diterbitkan! 📢');
    }

    public function destroyPengumuman(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return back()->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function storeGaleri(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'tanggal' => 'required|date',
            'foto' => 'required|image|max:4096', // maks 4MB
        ]);

        $path = $request->file('foto')->store('galeri', 'public');

        Galeri::create([
            'user_id' => auth()->id(),
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'file' => $path,
        ]);

        return back()->with('success', 'Foto dokumentasi berhasil diunggah! 📸');
    }

    public function destroyGaleri(Galeri $galeri)
    {
        if ($galeri->file && Storage::disk('public')->exists($galeri->file)) {
            Storage::disk('public')->delete($galeri->file);
        }

        $galeri->delete();

        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }
}
