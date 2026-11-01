<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumanList = Pengumuman::with('penulis')
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('anggota.pengumuman.index', compact('pengumumanList'));
    }

    public function show(Pengumuman $pengumuman)
    {
        return view('anggota.pengumuman.show', compact('pengumuman'));
    }
}
