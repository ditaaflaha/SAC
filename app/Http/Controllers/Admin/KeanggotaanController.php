<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KeanggotaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Anggota::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status_keanggotaan', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_keanggotaan', $request->jenis);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_wa', 'like', "%{$search}%");
            });
        }

        $anggotaList = $query->paginate(15)->withQueryString();

        $counts = [
            'total' => Anggota::count(),
            'aktif' => Anggota::where('status_keanggotaan', 'aktif')->count(),
            'menunggu' => Anggota::where('status_keanggotaan', 'menunggu')->count(),
            'nonaktif' => Anggota::where('status_keanggotaan', 'nonaktif')->count(),
        ];

        return view('admin.keanggotaan.index', compact('anggotaList', 'counts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_wa' => 'required|string|max:30',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'jenis_keanggotaan' => 'required|in:reguler,trial,private,pasif',
            'jenis_busur' => 'nullable|string|max:100',
            'username' => 'nullable|string|max:100|unique:users,username',
            'password' => 'nullable|string|min:6',
            'catatan' => 'nullable|string',
        ]);

        $anggota = Anggota::create([
            'nama' => $validated['nama'],
            'no_wa' => $validated['no_wa'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_keanggotaan' => $validated['jenis_keanggotaan'],
            'jenis_busur' => $validated['jenis_busur'] ?? null,
            'status_keanggotaan' => 'aktif',
            'tanggal_gabung' => now()->toDateString(),
            'catatan' => $validated['catatan'] ?? null,
        ]);

        $baseUsername = ! empty($validated['username'])
            ? Str::slug($validated['username'])
            : Str::slug(explode(' ', $anggota->nama)[0]).$anggota->id;

        $username = $baseUsername;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername.rand(10, 99);
        }

        $plainPassword = $validated['password'] ?: 'password123';

        User::create([
            'anggota_id' => $anggota->id,
            'name' => $anggota->nama,
            'username' => $username,
            'email' => $username.'@sacarchery.local',
            'password' => Hash::make($plainPassword),
            'role' => 'anggota',
            'must_change_password' => false,
        ]);

        return back()->with('success', "Anggota baru {$anggota->nama} berhasil didaftarkan! Akun Login — Username: {$username} | Password: {$plainPassword}");
    }

    public function show(Anggota $anggota)
    {
        $anggota->load(['user', 'skorTembakan' => fn ($q) => $q->latest()->take(10), 'pembayaran' => fn ($q) => $q->latest()->take(10), 'presensi.jadwalLatihan']);

        return view('admin.keanggotaan.show', compact('anggota'));
    }

    public function updateStatus(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'status_keanggotaan' => 'required|in:menunggu,aktif,nonaktif,ditolak',
            'jenis_keanggotaan' => 'required|in:reguler,pasif,trial,private',
            'catatan' => 'nullable|string',
        ]);

        $statusLama = $anggota->status_keanggotaan;

        $updateData = [
            'status_keanggotaan' => $validated['status_keanggotaan'],
            'jenis_keanggotaan' => $validated['jenis_keanggotaan'],
            'catatan' => $validated['catatan'] ?? $anggota->catatan,
        ];

        // Jika baru diaktifkan pertama kali dan belum ada tanggal gabung
        if ($validated['status_keanggotaan'] === 'aktif' && ! $anggota->tanggal_gabung) {
            $updateData['tanggal_gabung'] = now()->toDateString();
        }

        $anggota->update($updateData);

        // Jika diaktifkan dan belum punya akun user, buatkan otomatis
        $pesanAkun = '';
        if ($validated['status_keanggotaan'] === 'aktif' && ! $anggota->user) {
            $baseUsername = Str::slug(explode(' ', $anggota->nama)[0]).$anggota->id;
            $username = $baseUsername;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername.rand(10, 99);
            }

            $user = User::create([
                'anggota_id' => $anggota->id,
                'name' => $anggota->nama,
                'username' => $username,
                'email' => $username.'@sacarchery.local',
                'password' => Hash::make('password123'),
                'role' => 'anggota',
                'must_change_password' => true,
            ]);

            $pesanAkun = " Akun user otomatis dibuat! Username: {$username}, Password: password123";
        }

        return back()->with('success', "Data status {$anggota->nama} berhasil diperbarui!{$pesanAkun}");
    }

    public function resetPassword(Anggota $anggota)
    {
        $user = $anggota->user;
        if (! $user) {
            return back()->with('error', 'Anggota ini belum memiliki akun user.');
        }

        $user->update([
            'password' => Hash::make('password123'),
            'must_change_password' => true,
        ]);

        return back()->with('success', "Password {$anggota->nama} direset ke 'password123'. Anggota akan diminta ganti password saat login.");
    }

    public function destroy(Anggota $anggota)
    {
        $nama = $anggota->nama;

        $anggota->user?->delete();
        $anggota->delete();

        return redirect()->route('admin.keanggotaan')->with('success', "Data anggota {$nama} beserta akun loginnya berhasil dihapus.");
    }
}
