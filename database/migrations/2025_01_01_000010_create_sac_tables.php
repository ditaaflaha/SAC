<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat semua tabel domain SAC sesuai ERD blueprint.
     */
    public function up(): void
    {
        // ----------------------------------------------------------------
        // Jadwal latihan
        // ----------------------------------------------------------------
        Schema::create('jadwal_latihan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('lokasi');
            $table->enum('jenis', ['wajib', 'mandiri'])->default('wajib');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // ----------------------------------------------------------------
        // Presensi
        // ----------------------------------------------------------------
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->foreignId('jadwal_id')->constrained('jadwal_latihan')->cascadeOnDelete();
            $table->timestamp('waktu_hadir')->nullable();
            $table->timestamps();
            $table->unique(['anggota_id', 'jadwal_id']); // satu anggota satu kali per jadwal
        });

        // ----------------------------------------------------------------
        // Skor tembakan
        // ----------------------------------------------------------------
        Schema::create('skor_tembakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->date('tanggal');
            $table->unsignedTinyInteger('sesi_ke')->default(1);
            $table->unsignedSmallInteger('jarak_meter');
            $table->unsignedSmallInteger('jumlah_anak_panah')->default(36);
            $table->unsignedTinyInteger('jumlah_end')->nullable();
            $table->unsignedTinyInteger('anak_panah_per_end')->nullable();
            $table->unsignedSmallInteger('total_skor');
            $table->enum('status_validasi', ['menunggu', 'valid', 'ditolak'])->default('menunggu');
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // ----------------------------------------------------------------
        // Qty shoot harian
        // ----------------------------------------------------------------
        Schema::create('qty_shoot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->date('tanggal');
            $table->unsignedSmallInteger('jumlah_anak_panah');
            $table->timestamps();
        });

        // ----------------------------------------------------------------
        // Kejuaraan
        // ----------------------------------------------------------------
        Schema::create('kejuaraan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kejuaraan');
            $table->date('tanggal');
            $table->string('lokasi');
            $table->enum('tingkat', ['lokal', 'provinsi', 'nasional']);
            $table->string('link_ianseo')->nullable();
            $table->timestamps();
        });

        // ----------------------------------------------------------------
        // Hasil lomba
        // ----------------------------------------------------------------
        Schema::create('hasil_lomba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->foreignId('kejuaraan_id')->constrained('kejuaraan')->cascadeOnDelete();
            $table->string('nama_tim')->default('Sunnah Archery');
            $table->string('kategori');
            $table->unsignedSmallInteger('jarak_meter');
            $table->unsignedSmallInteger('skor');
            $table->unsignedSmallInteger('peringkat');
            $table->string('medali')->nullable(); // emas, perak, perunggu, dsb
            $table->timestamps();
        });

        // ----------------------------------------------------------------
        // Peringatan (dibuat otomatis oleh AnalisisService)
        // ----------------------------------------------------------------
        Schema::create('peringatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->date('bulan'); // selalu tgl 1 bulan tsb
            $table->enum('status', ['Tetap', 'Turun', 'Jarang Latihan']);
            $table->text('rekomendasi')->nullable();
            $table->boolean('sudah_ditindaklanjuti')->default(false);
            $table->timestamps();
            $table->unique(['anggota_id', 'bulan']); // satu record per anggota per bulan
        });

        // ----------------------------------------------------------------
        // Target latihan personal
        // ----------------------------------------------------------------
        Schema::create('target_latihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->constrained('anggota')->cascadeOnDelete();
            $table->enum('jenis_target', ['rata_rata_skor', 'qty_shoot', 'kehadiran']);
            $table->unsignedInteger('nilai_target');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->enum('status', ['berjalan', 'tercapai', 'tidak_tercapai'])->default('berjalan');
            $table->timestamps();
        });

        // ----------------------------------------------------------------
        // Pengaturan analisis — hanya satu baris
        // ----------------------------------------------------------------
        Schema::create('pengaturan_analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('batas_poin')->default(5);
            $table->unsignedTinyInteger('min_latihan_bulanan')->default(4);
            $table->unsignedTinyInteger('anak_panah_per_sesi')->default(36);
            $table->text('saran_naik')->nullable();
            $table->text('saran_tetap')->nullable();
            $table->text('saran_turun')->nullable();
            $table->text('saran_jarang')->nullable();
            $table->timestamps();
        });

        // ----------------------------------------------------------------
        // Pengumuman
        // ----------------------------------------------------------------
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul');
            $table->longText('isi');
            $table->date('tanggal');
            $table->boolean('publik')->default(true);
            $table->timestamps();
        });

        // ----------------------------------------------------------------
        // Galeri foto
        // ----------------------------------------------------------------
        Schema::create('galeri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul');
            $table->string('file');
            $table->date('tanggal');
            $table->timestamps();
        });

        // ----------------------------------------------------------------
        // Tarif (bisa berubah, berlaku mulai tanggal tertentu)
        // ----------------------------------------------------------------
        Schema::create('tarif', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['trial', 'private', 'pendaftaran', 'iuran_bulanan', 'member_pasif']);
            $table->unsignedInteger('nominal');
            $table->string('satuan'); // "per pertemuan", "6 kali pertemuan", dsb
            $table->date('berlaku_mulai');
            $table->timestamps();
        });

        // ----------------------------------------------------------------
        // Pembayaran / Iuran
        // ----------------------------------------------------------------
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->nullable()->constrained('anggota')->nullOnDelete();
            $table->foreignId('tarif_id')->constrained('tarif')->restrictOnDelete();
            $table->string('nama_peserta')->nullable(); // untuk trial/private tanpa akun
            $table->date('tanggal_bayar');
            $table->date('periode')->nullable(); // tgl 1 bulan iuran, nullable untuk non-iuran
            $table->unsignedTinyInteger('cicilan_ke')->nullable(); // 1 atau 2
            $table->unsignedInteger('jumlah');
            $table->string('bukti_transfer')->nullable(); // path file
            $table->enum('status', ['menunggu', 'lunas', 'ditolak', 'free'])->default('menunggu');
            $table->text('alasan_tolak')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
        Schema::dropIfExists('tarif');
        Schema::dropIfExists('galeri');
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('pengaturan_analisis');
        Schema::dropIfExists('target_latihan');
        Schema::dropIfExists('peringatan');
        Schema::dropIfExists('hasil_lomba');
        Schema::dropIfExists('kejuaraan');
        Schema::dropIfExists('qty_shoot');
        Schema::dropIfExists('skor_tembakan');
        Schema::dropIfExists('presensi');
        Schema::dropIfExists('jadwal_latihan');
    }
};
