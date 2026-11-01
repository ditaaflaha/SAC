<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Galeri;
use App\Models\HasilLomba;
use App\Models\JadwalLatihan;
use App\Models\Kejuaraan;
use App\Models\Pembayaran;
use App\Models\PengaturanAnalisis;
use App\Models\Pengumuman;
use App\Models\Presensi;
use App\Models\QtyShoot;
use App\Models\SkorTembakan;
use App\Models\Tarif;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------------------------------------------------------
        // 1. Akun Admin
        // ----------------------------------------------------------------
        $admin = User::create([
            'anggota_id' => null,
            'name' => 'Admin SAC',
            'username' => 'admin',
            'email' => 'admin@sacarchery.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'must_change_password' => false,
        ]);

        // ----------------------------------------------------------------
        // 2. Pengaturan Analisis (default)
        // ----------------------------------------------------------------
        PengaturanAnalisis::create([
            'user_id' => $admin->id,
            'batas_poin' => 5,
            'min_latihan_bulanan' => 4,
            'anak_panah_per_sesi' => 36,
            'saran_naik' => 'Pertahankan ritme ini dan naikkan target kamu! 🏆',
            'saran_tetap' => 'Coba variasi jarak atau latihan teknik, dan konsultasi dengan pelatih.',
            'saran_turun' => 'Evaluasi teknik, istirahat yang cukup, dan konsultasi dengan pelatih.',
            'saran_jarang' => 'Tambah frekuensi latihan (minimal 2 kali seminggu). Konsistensi adalah kunci!',
        ]);

        // ----------------------------------------------------------------
        // 3. Tarif (sesuai blueprint Bagian 4.4)
        // ----------------------------------------------------------------
        $tarifData = [
            ['jenis' => 'trial',          'nominal' => 75000,  'satuan' => 'per pertemuan',      'berlaku_mulai' => '2024-01-01'],
            ['jenis' => 'private',        'nominal' => 500000, 'satuan' => '6 kali pertemuan',   'berlaku_mulai' => '2024-01-01'],
            ['jenis' => 'pendaftaran',    'nominal' => 350000, 'satuan' => 'sekali',              'berlaku_mulai' => '2024-01-01'],
            ['jenis' => 'iuran_bulanan',  'nominal' => 40000,  'satuan' => 'per bulan',           'berlaku_mulai' => '2024-01-01'],
            ['jenis' => 'member_pasif',   'nominal' => 50000,  'satuan' => 'per kunjungan',       'berlaku_mulai' => '2026-01-01'],
        ];

        foreach ($tarifData as $t) {
            Tarif::create($t);
        }

        $tarifIuran = Tarif::where('jenis', 'iuran_bulanan')->first();

        // ----------------------------------------------------------------
        // 4. Pengumuman publik
        // ----------------------------------------------------------------
        Pengumuman::create([
            'user_id' => $admin->id,
            'judul' => 'Selamat Datang di Website SAC!',
            'isi' => 'Website resmi Sunnah Archery Community kini hadir! Pantau progres, jadwal, dan info terbaru di sini.',
            'tanggal' => Carbon::now()->subDays(2)->toDateString(),
            'publik' => true,
        ]);

        Pengumuman::create([
            'user_id' => $admin->id,
            'judul' => 'Latihan Wajib Ahad Pagi',
            'isi' => 'Latihan wajib dilaksanakan setiap hari Ahad pukul 07.00 WIB di Lapangan Archery SAC. Harap hadir tepat waktu!',
            'tanggal' => Carbon::now()->subDays(5)->toDateString(),
            'publik' => true,
        ]);

        // ----------------------------------------------------------------
        // 5. Jadwal latihan dummy (4 jadwal terakhir)
        // ----------------------------------------------------------------
        $jadwalList = [];
        for ($i = 3; $i >= 0; $i--) {
            // Jadwal setiap Ahad
            $tanggal = Carbon::now()->startOfWeek(Carbon::SUNDAY)->subWeeks($i);
            $jadwal = JadwalLatihan::create([
                'tanggal' => $tanggal->toDateString(),
                'waktu_mulai' => '07:00:00',
                'waktu_selesai' => '09:00:00',
                'lokasi' => 'Lapangan Archery SAC, Batam',
                'jenis' => 'wajib',
                'keterangan' => 'Latihan rutin mingguan',
            ]);
            $jadwalList[] = $jadwal;
        }

        // ----------------------------------------------------------------
        // 6. 22 Anggota dummy dengan skor 3 bulan terakhir
        // ----------------------------------------------------------------
        $namaList = [
            ['Ahmad Fauzi', 'L'],
            ['Budi Santoso', 'L'],
            ['Cahya Ramadhani', 'P'],
            ['Dewi Anggraini', 'P'],
            ['Eko Prasetyo', 'L'],
            ['Fatimah Azzahra', 'P'],
            ['Gilang Permana', 'L'],
            ['Hana Aulia', 'P'],
            ['Irfan Maulana', 'L'],
            ['Juliani Putri', 'P'],
            ['Khairul Anwar', 'L'],
            ['Laila Rahma', 'P'],
            ['Muhammad Rizki', 'L'],
            ['Nadia Sari', 'P'],
            ['Omar Abdillah', 'L'],
            ['Putri Wulandari', 'P'],
            ['Qori Ramadhan', 'L'],
            ['Rina Permatasari', 'P'],
            ['Surya Hidayat', 'L'],
            ['Tika Andriani', 'P'],
            ['Umar Hakim', 'L'],
            ['Vivi Oktaviani', 'P'],
        ];

        // Pola skor untuk menghasilkan campuran status
        // [bulan_lalu_avg, bulan_ini_avg, jumlah_sesi_bulan_ini]
        $polaSkor = [
            [290, 305, 4], // Naik (+15)
            [300, 302, 4], // Tetap (+2)
            [290, 278, 4], // Turun (-12)
            [280, 295, 4], // Naik (+15)
            [310, 312, 4], // Tetap (+2)
            [295, 280, 4], // Turun (-15)
            [285, 300, 4], // Naik (+15)
            [300, 301, 4], // Tetap (+1)
            [305, 290, 4], // Turun (-15)
            [270, 285, 4], // Naik (+15)
            [null, 280, 3], // Jarang Latihan (3 sesi)
            [295, 295, 3], // Jarang Latihan
            [280, 295, 4], // Naik
            [290, 292, 4], // Tetap
            [300, 285, 4], // Turun
            [275, 290, 4], // Naik
            [285, 287, 4], // Tetap
            [295, 278, 4], // Turun
            [null, 295, 4], // Belum Ada Pembanding
            [270, 288, 4], // Naik
            [280, 278, 4], // Tetap (selisih -2 belum capai batas)
            [300, 300, 3], // Jarang Latihan
        ];

        $bulanIni = Carbon::now()->startOfMonth();
        $bulanLalu = $bulanIni->copy()->subMonth();
        $dua_bulan_lalu = $bulanIni->copy()->subMonths(2);

        foreach ($namaList as $idx => [$nama, $gender]) {
            // Tanggal gabung: 3 bulan lalu (sudah lewat 2 bulan gratis)
            $tanggalGabung = $dua_bulan_lalu->copy()->subMonth();

            $anggota = Anggota::create([
                'nama' => $nama,
                'no_wa' => '0812'.str_pad(rand(10000000, 99999999), 8, '0'),
                'jenis_kelamin' => $gender,
                'tanggal_lahir' => Carbon::now()->subYears(rand(17, 35))->toDateString(),
                'jenis_busur' => rand(0, 1) ? 'milik sendiri' : null,
                'jenis_keanggotaan' => 'reguler',
                'status_keanggotaan' => 'aktif',
                'tanggal_gabung' => $tanggalGabung->toDateString(),
            ]);

            // Buat akun user untuk anggota
            $username = Str::slug(explode(' ', $nama)[0]).$anggota->id;
            User::create([
                'anggota_id' => $anggota->id,
                'name' => $nama,
                'username' => $username,
                'email' => Str::slug($nama).'@example.com',
                'password' => Hash::make('password'),
                'role' => 'anggota',
                'must_change_password' => false,
            ]);

            [$avgLalu, $avgIni, $jumlahSesiIni] = $polaSkor[$idx];

            // Buat skor bulan lalu (4 sesi valid)
            if ($avgLalu !== null) {
                for ($s = 1; $s <= 4; $s++) {
                    $variasi = rand(-5, 5);
                    $tanggal = $bulanLalu->copy()->addDays(($s - 1) * 6 + 1);
                    SkorTembakan::create([
                        'anggota_id' => $anggota->id,
                        'tanggal' => $tanggal->toDateString(),
                        'sesi_ke' => $s,
                        'jarak_meter' => 18,
                        'jumlah_anak_panah' => 36,
                        'total_skor' => $avgLalu + $variasi,
                        'status_validasi' => 'valid',
                        'divalidasi_oleh' => $admin->id,
                    ]);
                }
            }

            // Buat skor bulan ini
            for ($s = 1; $s <= $jumlahSesiIni; $s++) {
                $variasi = rand(-5, 5);
                $tanggal = $bulanIni->copy()->addDays(($s - 1) * 6 + 1);
                SkorTembakan::create([
                    'anggota_id' => $anggota->id,
                    'tanggal' => $tanggal->toDateString(),
                    'sesi_ke' => $s,
                    'jarak_meter' => 18,
                    'jumlah_anak_panah' => 36,
                    'total_skor' => $avgIni + $variasi,
                    'status_validasi' => 'valid',
                    'divalidasi_oleh' => $admin->id,
                ]);
            }

            // QtyShoot harian
            for ($d = 1; $d <= 20; $d++) {
                $tgl = $bulanIni->copy()->addDays($d - 1);
                if ($tgl->lte(Carbon::now())) {
                    QtyShoot::create([
                        'anggota_id' => $anggota->id,
                        'tanggal' => $tgl->toDateString(),
                        'jumlah_anak_panah' => rand(36, 120),
                    ]);
                }
            }

            // Presensi (hadir di 2-3 jadwal terakhir)
            $hadirCount = rand(2, min(4, count($jadwalList)));
            foreach (array_slice($jadwalList, -$hadirCount) as $jadwal) {
                Presensi::create([
                    'anggota_id' => $anggota->id,
                    'jadwal_id' => $jadwal->id,
                    'waktu_hadir' => $jadwal->tanggal->setTimeFromTimeString('07:'.rand(0, 15).':00'),
                ]);
            }

            // Iuran: campuran status
            $this->buatIuran($anggota, $tarifIuran, $admin, $bulanIni, $bulanLalu, $idx);
        }

        // ----------------------------------------------------------------
        // 7. Anggota pendaftar baru (status menunggu)
        // ----------------------------------------------------------------
        Anggota::create([
            'nama' => 'Zahra Khoirunnisa',
            'no_wa' => '08137654321',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2006-03-15',
            'jenis_keanggotaan' => 'trial',
            'status_keanggotaan' => 'menunggu',
        ]);

        Anggota::create([
            'nama' => 'Yusuf Abidin',
            'no_wa' => '08129876543',
            'jenis_kelamin' => 'L',
            'jenis_keanggotaan' => 'reguler',
            'status_keanggotaan' => 'menunggu',
        ]);

        // ----------------------------------------------------------------
        // 8. Kejuaraan & hasil lomba dummy
        // ----------------------------------------------------------------
        $k1 = Kejuaraan::create([
            'nama_kejuaraan' => 'Open Archery Batam 2025',
            'tanggal' => '2025-08-17',
            'lokasi' => 'GOR Temenggung Abdul Jamal, Batam',
            'tingkat' => 'lokal',
            'link_ianseo' => null,
        ]);

        $k2 = Kejuaraan::create([
            'nama_kejuaraan' => 'Kejurprov Kepri 2025',
            'tanggal' => '2025-10-05',
            'lokasi' => 'Lapangan Panahan Tanjungpinang',
            'tingkat' => 'provinsi',
            'link_ianseo' => null,
        ]);

        // Ambil 5 anggota pertama untuk hasil lomba
        $peserta = Anggota::where('status_keanggotaan', 'aktif')->take(5)->get();
        $medali = ['Emas', 'Perak', 'Perunggu', null, null];

        foreach ($peserta as $i => $p) {
            HasilLomba::create([
                'anggota_id' => $p->id,
                'kejuaraan_id' => $k1->id,
                'nama_tim' => 'Sunnah Archery',
                'kategori' => $p->jenis_kelamin === 'L' ? 'Recurve Putra' : 'Recurve Putri',
                'jarak_meter' => 18,
                'skor' => rand(270, 320),
                'peringkat' => $i + 1,
                'medali' => $medali[$i],
            ]);
        }

        foreach ($peserta->take(3) as $i => $p) {
            HasilLomba::create([
                'anggota_id' => $p->id,
                'kejuaraan_id' => $k2->id,
                'nama_tim' => 'Warrior X Sunnah',
                'kategori' => $p->jenis_kelamin === 'L' ? 'Compound Putra' : 'Compound Putri',
                'jarak_meter' => 30,
                'skor' => rand(260, 310),
                'peringkat' => $i + 1,
                'medali' => $medali[$i],
            ]);
        }

        // ----------------------------------------------------------------
        // 9. Galeri foto dummy
        // ----------------------------------------------------------------
        for ($g = 1; $g <= 3; $g++) {
            Galeri::create([
                'user_id' => $admin->id,
                'judul' => "Latihan Rutin SAC #$g",
                'file' => "galeri/placeholder-$g.jpg",
                'tanggal' => Carbon::now()->subWeeks($g)->toDateString(),
            ]);
        }
    }

    /**
     * Buat data iuran dengan campuran status berdasarkan index anggota.
     */
    private function buatIuran(
        Anggota $anggota,
        Tarif $tarif,
        User $admin,
        Carbon $bulanIni,
        Carbon $bulanLalu,
        int $idx
    ): void {
        // Pola iuran berulang berdasarkan modulo
        $pola = $idx % 5;

        match ($pola) {
            // Lunas penuh bulan ini
            0 => Pembayaran::create([
                'anggota_id' => $anggota->id,
                'tarif_id' => $tarif->id,
                'tanggal_bayar' => $bulanIni->copy()->addDays(3)->toDateString(),
                'periode' => $bulanIni->toDateString(),
                'jumlah' => 40000,
                'status' => 'lunas',
                'diverifikasi_oleh' => $admin->id,
            ]),

            // Cicilan pertama saja (C1)
            1 => Pembayaran::create([
                'anggota_id' => $anggota->id,
                'tarif_id' => $tarif->id,
                'tanggal_bayar' => $bulanIni->copy()->addDays(2)->toDateString(),
                'periode' => $bulanIni->toDateString(),
                'cicilan_ke' => 1,
                'jumlah' => 20000,
                'status' => 'lunas',
                'diverifikasi_oleh' => $admin->id,
            ]),

            // C1 + C2 (lunas cicilan)
            2 => $this->buatCicilanLunas($anggota, $tarif, $admin, $bulanIni),

            // FREE (buat sebagai record free)
            3 => Pembayaran::create([
                'anggota_id' => $anggota->id,
                'tarif_id' => $tarif->id,
                'tanggal_bayar' => $bulanIni->toDateString(),
                'periode' => $bulanIni->toDateString(),
                'jumlah' => 0,
                'status' => 'free',
                'diverifikasi_oleh' => $admin->id,
            ]),

            // Belum bayar → tidak buat record
            default => null,
        };

        // Bulan lalu: semua lunas (untuk rekap historis)
        if ($idx < 10) {
            Pembayaran::create([
                'anggota_id' => $anggota->id,
                'tarif_id' => $tarif->id,
                'tanggal_bayar' => $bulanLalu->copy()->addDays(5)->toDateString(),
                'periode' => $bulanLalu->toDateString(),
                'jumlah' => 40000,
                'status' => 'lunas',
                'diverifikasi_oleh' => $admin->id,
            ]);
        }
    }

    private function buatCicilanLunas(Anggota $anggota, Tarif $tarif, User $admin, Carbon $bulan): void
    {
        Pembayaran::create([
            'anggota_id' => $anggota->id,
            'tarif_id' => $tarif->id,
            'tanggal_bayar' => $bulan->copy()->addDays(1)->toDateString(),
            'periode' => $bulan->toDateString(),
            'cicilan_ke' => 1,
            'jumlah' => 20000,
            'status' => 'lunas',
            'diverifikasi_oleh' => $admin->id,
        ]);

        Pembayaran::create([
            'anggota_id' => $anggota->id,
            'tarif_id' => $tarif->id,
            'tanggal_bayar' => $bulan->copy()->addDays(15)->toDateString(),
            'periode' => $bulan->toDateString(),
            'cicilan_ke' => 2,
            'jumlah' => 20000,
            'status' => 'lunas',
            'diverifikasi_oleh' => $admin->id,
        ]);
    }
}
