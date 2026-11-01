<?php

use App\Http\Controllers\Admin\AnalisisController as AdminAnalisis;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\HasilLombaController as AdminLomba;
use App\Http\Controllers\Admin\IuranController as AdminIuran;
use App\Http\Controllers\Admin\JadwalController as AdminJadwal;
use App\Http\Controllers\Admin\KeanggotaanController as AdminKeanggotaan;
use App\Http\Controllers\Admin\LeaderboardController as AdminLeaderboard;
use App\Http\Controllers\Admin\PengaturanAnalisisController as AdminPengaturan;
use App\Http\Controllers\Admin\PengumumanGaleriController as AdminPengumuman;
use App\Http\Controllers\Admin\ProgresController as AdminProgres;
use App\Http\Controllers\Admin\ValidasiSkorController as AdminValidasiSkor;
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboard;
use App\Http\Controllers\Anggota\IuranController as AnggotaIuran;
use App\Http\Controllers\Anggota\JadwalController as AnggotaJadwal;
use App\Http\Controllers\Anggota\LeaderboardController as AnggotaLeaderboard;
use App\Http\Controllers\Anggota\PengumumanController as AnggotaPengumuman;
use App\Http\Controllers\Anggota\ProgresController as AnggotaProgres;
use App\Http\Controllers\Anggota\SkorController as AnggotaSkor;
use App\Http\Controllers\Anggota\TargetController as AnggotaTarget;
use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublikController;
use Illuminate\Support\Facades\Route;

// ============================================================
// RUTE PUBLIK (tanpa login)
// ============================================================
Route::get('/', [PublikController::class, 'beranda'])->name('beranda');
Route::get('/profil', [PublikController::class, 'profil'])->name('profil');
Route::get('/dokumentasi', [PublikController::class, 'dokumentasi'])->name('dokumentasi');
Route::get('/daftar', fn () => redirect()->route('login'))->name('daftar');

// ============================================================
// REDIRECT /dashboard → dashboard sesuai role
// ============================================================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('anggota.dashboard');
    })->name('dashboard');

    // Ganti password wajib (untuk anggota baru)
    Route::get('/ganti-password', [PasswordChangeController::class, 'edit'])->name('password.change');
    Route::put('/ganti-password', [PasswordChangeController::class, 'update'])->name('password.change.update');

    // Profile akun
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================
// RUTE ANGGOTA
// ============================================================
Route::middleware(['auth', 'role:anggota'])->prefix('anggota')->name('anggota.')->group(function () {
    Route::get('/dashboard', [AnggotaDashboard::class, 'index'])->name('dashboard');

    // Jadwal & Presensi
    Route::get('/jadwal', [AnggotaJadwal::class, 'index'])->name('jadwal');
    Route::post('/jadwal/{jadwal}/presensi', [AnggotaJadwal::class, 'presensi'])->name('jadwal.presensi');

    // Input Skor & Qty Shoot
    Route::get('/skor', [AnggotaSkor::class, 'index'])->name('skor');
    Route::post('/skor', [AnggotaSkor::class, 'store'])->name('skor.store');
    Route::post('/skor/qty', [AnggotaSkor::class, 'storeQty'])->name('skor.qty.store');

    // Progres Saya
    Route::get('/progres', [AnggotaProgres::class, 'index'])->name('progres');
    Route::get('/progres/kta', [AnggotaProgres::class, 'ktaPdf'])->name('progres.kta');

    // Leaderboard
    Route::get('/leaderboard', [AnggotaLeaderboard::class, 'index'])->name('leaderboard');

    // Target Latihan & Rekomendasi
    Route::get('/target', [AnggotaTarget::class, 'index'])->name('target');
    Route::post('/target', [AnggotaTarget::class, 'store'])->name('target.store');
    Route::delete('/target/rekomendasi', [AnggotaTarget::class, 'destroyRekomendasi'])->name('target.rekomendasi.destroy');
    Route::delete('/target/{target}', [AnggotaTarget::class, 'destroy'])->name('target.destroy');

    // Iuran Saya
    Route::get('/iuran', [AnggotaIuran::class, 'index'])->name('iuran');
    Route::post('/iuran/bayar', [AnggotaIuran::class, 'bayar'])->name('iuran.bayar');

    // Pengumuman
    Route::get('/pengumuman', [AnggotaPengumuman::class, 'index'])->name('pengumuman');
    Route::get('/pengumuman/{pengumuman}', [AnggotaPengumuman::class, 'show'])->name('pengumuman.show');
});

// ============================================================
// RUTE ADMIN
// ============================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Keanggotaan
    Route::get('/keanggotaan', [AdminKeanggotaan::class, 'index'])->name('keanggotaan');
    Route::post('/keanggotaan', [AdminKeanggotaan::class, 'store'])->name('keanggotaan.store');
    Route::get('/keanggotaan/{anggota}', [AdminKeanggotaan::class, 'show'])->name('keanggotaan.show');
    Route::put('/keanggotaan/{anggota}/status', [AdminKeanggotaan::class, 'updateStatus'])->name('keanggotaan.status');
    Route::post('/keanggotaan/{anggota}/reset-password', [AdminKeanggotaan::class, 'resetPassword'])->name('keanggotaan.reset-password');
    Route::delete('/keanggotaan/{anggota}', [AdminKeanggotaan::class, 'destroy'])->name('keanggotaan.destroy');

    // Jadwal & Presensi
    Route::get('/jadwal', [AdminJadwal::class, 'index'])->name('jadwal');
    Route::post('/jadwal', [AdminJadwal::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{jadwal}', [AdminJadwal::class, 'destroy'])->name('jadwal.destroy');
    Route::get('/jadwal/{jadwal}/presensi', [AdminJadwal::class, 'presensi'])->name('jadwal.presensi');
    Route::post('/jadwal/{jadwal}/presensi/toggle', [AdminJadwal::class, 'presensiToggle'])->name('jadwal.presensi.toggle');

    // Validasi Skor
    Route::get('/validasi-skor', [AdminValidasiSkor::class, 'index'])->name('validasi-skor');
    Route::put('/validasi-skor/{skor}', [AdminValidasiSkor::class, 'validasi'])->name('validasi-skor.update');

    // Progres Anggota
    Route::get('/progres', [AdminProgres::class, 'index'])->name('progres');
    Route::get('/progres/{anggota}', [AdminProgres::class, 'show'])->name('progres.show');
    Route::get('/progres/{anggota}/pdf', [AdminProgres::class, 'exportPdf'])->name('progres.pdf');

    // Leaderboard
    Route::get('/leaderboard', [AdminLeaderboard::class, 'index'])->name('leaderboard');

    // Dashboard Analisis
    Route::get('/analisis', [AdminAnalisis::class, 'index'])->name('analisis');
    Route::post('/analisis/peringatan/{peringatan}/tindaklanjuti', [AdminAnalisis::class, 'tindaklanjuti'])->name('analisis.tindaklanjuti');
    Route::delete('/analisis/peringatan/{peringatan}', [AdminAnalisis::class, 'destroy'])->name('analisis.destroy');

    // Hasil Lomba
    Route::get('/lomba', [AdminLomba::class, 'index'])->name('lomba');
    Route::post('/lomba/kejuaraan', [AdminLomba::class, 'storeKejuaraan'])->name('lomba.kejuaraan.store');
    Route::put('/lomba/kejuaraan/{kejuaraan}', [AdminLomba::class, 'updateKejuaraan'])->name('lomba.kejuaraan.update');
    Route::delete('/lomba/kejuaraan/{kejuaraan}', [AdminLomba::class, 'destroyKejuaraan'])->name('lomba.kejuaraan.destroy');
    Route::post('/lomba/hasil', [AdminLomba::class, 'storeHasil'])->name('lomba.hasil.store');
    Route::put('/lomba/hasil/{hasil}', [AdminLomba::class, 'updateHasil'])->name('lomba.hasil.update');
    Route::delete('/lomba/hasil/{hasil}', [AdminLomba::class, 'destroyHasil'])->name('lomba.hasil.destroy');

    // Iuran & Kas
    Route::get('/iuran', [AdminIuran::class, 'index'])->name('iuran');
    Route::get('/iuran/pdf', [AdminIuran::class, 'exportPdf'])->name('iuran.pdf');
    Route::put('/iuran/{pembayaran}/verifikasi', [AdminIuran::class, 'verifikasi'])->name('iuran.verifikasi');
    Route::post('/iuran/manual', [AdminIuran::class, 'storeManual'])->name('iuran.manual');
    Route::put('/iuran/tarif/{tarif}', [AdminIuran::class, 'updateTarif'])->name('iuran.tarif.update');

    // Pengumuman & Galeri
    Route::get('/pengumuman', [AdminPengumuman::class, 'index'])->name('pengumuman');
    Route::post('/pengumuman', [AdminPengumuman::class, 'storePengumuman'])->name('pengumuman.store');
    Route::delete('/pengumuman/{pengumuman}', [AdminPengumuman::class, 'destroyPengumuman'])->name('pengumuman.destroy');
    Route::post('/galeri', [AdminPengumuman::class, 'storeGaleri'])->name('galeri.store');
    Route::delete('/galeri/{galeri}', [AdminPengumuman::class, 'destroyGaleri'])->name('galeri.destroy');

    // Pengaturan Analisis
    Route::get('/pengaturan', [AdminPengaturan::class, 'index'])->name('pengaturan');
    Route::put('/pengaturan', [AdminPengaturan::class, 'update'])->name('pengaturan.update');
});

// Auth routes dari Breeze
require __DIR__.'/auth.php';
