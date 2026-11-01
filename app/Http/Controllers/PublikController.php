<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Galeri;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PublikController extends Controller
{
    /** Halaman Beranda */
    public function beranda()
    {
        $pengumuman = Pengumuman::publik()
            ->orderByDesc('tanggal')
            ->take(3)
            ->get();

        $galeri = Galeri::orderByDesc('tanggal')->take(6)->get();

        return view('publik.beranda', compact('pengumuman', 'galeri'));
    }

    /** Halaman Profil SAC */
    public function profil()
    {
        return view('publik.profil');
    }

    /** Halaman Dokumentasi / Galeri */
    public function dokumentasi()
    {
        $galeri = Galeri::orderByDesc('tanggal')->paginate(12);

        return view('publik.dokumentasi', compact('galeri'));
    }

    /** Form Pendaftaran */
    public function daftar()
    {
        return view('publik.daftar');
    }

    /** Proses submit form pendaftaran */
    public function daftarStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'no_wa' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'punya_busur' => 'required|in:ya,tidak',
            'jenis_pilihan' => 'required|in:trial,private,reguler',
            'follow_ig' => 'accepted',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'jenis_kelamin.required' => 'Pilih jenis kelamin.',
            'punya_busur.required' => 'Pilih kepemilikan busur.',
            'jenis_pilihan.required' => 'Pilih jenis keanggotaan yang diinginkan.',
            'follow_ig.accepted' => 'Kamu harus follow IG SAC dulu ya!',
        ]);

        $anggota = Anggota::create([
            'nama' => $validated['nama'],
            'no_wa' => $validated['no_wa'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_busur' => $validated['punya_busur'] === 'ya' ? 'milik sendiri' : null,
            'jenis_keanggotaan' => $validated['jenis_pilihan'],
            'status_keanggotaan' => 'menunggu',
        ]);

        // Simpan id ke session untuk halaman berhasil
        session(['pendaftaran_nama' => $anggota->nama, 'pendaftaran_pilihan' => $validated['jenis_pilihan']]);

        return redirect()->route('daftar.berhasil');
    }

    /** Halaman konfirmasi pendaftaran berhasil */
    public function daftarBerhasil()
    {
        if (! session('pendaftaran_nama')) {
            return redirect()->route('daftar');
        }

        $adminWa = config('app.sac_admin_wa', env('SAC_ADMIN_WA', '628123456789'));
        $nama = session('pendaftaran_nama');
        $pilihan = session('pendaftaran_pilihan');

        $pesan = urlencode("Halo Kak, saya {$nama} baru saja mendaftar di Sunnah Archery Community dengan pilihan: {$pilihan}. Mohon konfirmasinya. Terima kasih! 🏹");
        $waLink = "https://wa.me/{$adminWa}?text={$pesan}";

        return view('publik.daftar-berhasil', compact('waLink', 'nama'));
    }
}
