<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\HasilLomba;
use App\Models\JadwalLatihan;
use App\Models\Kejuaraan;
use App\Models\PengaturanAnalisis;
use App\Models\Peringatan;
use App\Models\SkorTembakan;
use App\Models\Tarif;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPortalTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $anggotaUser;

    private Anggota $anggota;

    protected function setUp(): void
    {
        parent::setUp();

        PengaturanAnalisis::aktif();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->anggota = Anggota::create([
            'nama' => 'Pemanah Test',
            'no_wa' => '081234567890',
            'jenis_kelamin' => 'L',
            'jenis_keanggotaan' => 'reguler',
            'status_keanggotaan' => 'aktif',
            'tanggal_gabung' => now()->subMonths(3)->toDateString(),
        ]);

        $this->anggotaUser = User::factory()->create([
            'anggota_id' => $this->anggota->id,
            'role' => 'anggota',
        ]);

        Tarif::create([
            'jenis' => 'iuran_bulanan',
            'nominal' => 40000,
            'satuan' => 'per bulan',
            'berlaku_mulai' => '2024-01-01',
        ]);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Ringkasan');
    }

    public function test_anggota_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->anggotaUser)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_keanggotaan_and_update_status(): void
    {
        $pendaftar = Anggota::create([
            'nama' => 'Calon Anggota',
            'no_wa' => '08111222333',
            'jenis_kelamin' => 'P',
            'jenis_keanggotaan' => 'trial',
            'status_keanggotaan' => 'menunggu',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.keanggotaan'));
        $response->assertStatus(200);
        $response->assertSee('Calon Anggota');

        // Approve and activate
        $update = $this->actingAs($this->admin)->put(route('admin.keanggotaan.status', $pendaftar), [
            'status_keanggotaan' => 'aktif',
            'jenis_keanggotaan' => 'reguler',
            'catatan' => 'Disetujui admin',
        ]);

        $update->assertRedirect();
        $this->assertDatabaseHas('anggota', [
            'id' => $pendaftar->id,
            'status_keanggotaan' => 'aktif',
        ]);
        // Auto user account created
        $this->assertDatabaseHas('users', [
            'anggota_id' => $pendaftar->id,
            'role' => 'anggota',
        ]);

        // Admin can also directly register a new member
        $create = $this->actingAs($this->admin)->post(route('admin.keanggotaan.store'), [
            'nama' => 'Anggota Baru Admin',
            'no_wa' => '081299988877',
            'jenis_kelamin' => 'L',
            'jenis_keanggotaan' => 'reguler',
            'username' => 'anggotabaru',
            'password' => 'password123',
        ]);

        $create->assertRedirect();
        $this->assertDatabaseHas('anggota', [
            'nama' => 'Anggota Baru Admin',
            'status_keanggotaan' => 'aktif',
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'anggotabaru',
            'role' => 'anggota',
        ]);

        // Admin can delete a member and their user account
        $anggotaBaru = Anggota::where('nama', 'Anggota Baru Admin')->first();
        $delete = $this->actingAs($this->admin)->delete(route('admin.keanggotaan.destroy', $anggotaBaru));
        $delete->assertRedirect(route('admin.keanggotaan'));
        $this->assertDatabaseMissing('anggota', ['id' => $anggotaBaru->id]);
        $this->assertDatabaseMissing('users', ['username' => 'anggotabaru']);
    }

    public function test_admin_can_create_jadwal_and_toggle_presensi(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.jadwal.store'), [
            'tanggal' => now()->toDateString(),
            'waktu_mulai' => '07:00',
            'waktu_selesai' => '09:00',
            'lokasi' => 'Lapangan SAC',
            'jenis' => 'wajib',
            'keterangan' => 'Latihan rutin Ahad',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jadwal_latihan', [
            'lokasi' => 'Lapangan SAC',
        ]);

        $jadwal = JadwalLatihan::first();

        // Mark presensi
        $presensi = $this->actingAs($this->admin)->post(route('admin.jadwal.presensi.toggle', $jadwal), [
            'anggota_id' => $this->anggota->id,
        ]);

        $presensi->assertRedirect();
        $this->assertDatabaseHas('presensi', [
            'jadwal_id' => $jadwal->id,
            'anggota_id' => $this->anggota->id,
        ]);
    }

    public function test_admin_can_validate_skor(): void
    {
        $skor = SkorTembakan::create([
            'anggota_id' => $this->anggota->id,
            'tanggal' => now()->toDateString(),
            'sesi_ke' => 1,
            'jarak_meter' => 18,
            'jumlah_anak_panah' => 36,
            'total_skor' => 310,
            'status_validasi' => 'menunggu',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.validasi-skor.update', $skor), [
            'status_validasi' => 'valid',
            'catatan' => 'Skor sah',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('skor_tembakan', [
            'id' => $skor->id,
            'status_validasi' => 'valid',
            'divalidasi_oleh' => $this->admin->id,
        ]);
    }

    public function test_admin_can_view_analisis_and_follow_up_warning(): void
    {
        $peringatan = Peringatan::create([
            'anggota_id' => $this->anggota->id,
            'bulan' => now()->startOfMonth()->toDateString(),
            'status' => 'Tetap',
            'rekomendasi' => 'Latihan teknik dasar',
            'sudah_ditindaklanjuti' => false,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.analisis.tindaklanjuti', $peringatan));
        $response->assertRedirect();
        $this->assertTrue($peringatan->fresh()->sudah_ditindaklanjuti);
    }

    public function test_admin_can_export_iuran_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.iuran.pdf'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_export_progres_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.progres.pdf', $this->anggota));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_create_and_edit_hasil_lomba(): void
    {
        $kejuaraan = Kejuaraan::create([
            'nama_kejuaraan' => 'Kejuaraan Batam Open',
            'tanggal' => now()->toDateString(),
            'lokasi' => 'Batam',
            'tingkat' => 'lokal',
        ]);

        $store = $this->actingAs($this->admin)->post(route('admin.lomba.hasil.store'), [
            'kejuaraan_id' => $kejuaraan->id,
            'anggota_id' => $this->anggota->id,
            'nama_tim' => 'Sunnah Archery',
            'kategori' => 'Recurve Putra',
            'jarak_meter' => 18,
            'skor' => 300,
            'peringkat' => 2,
            'medali' => 'Perak',
        ]);

        $store->assertRedirect();
        $hasil = HasilLomba::first();
        $this->assertNotNull($hasil);

        $update = $this->actingAs($this->admin)->put(route('admin.lomba.hasil.update', $hasil), [
            'kejuaraan_id' => $kejuaraan->id,
            'anggota_id' => $this->anggota->id,
            'nama_tim' => 'Sunnah Archery',
            'kategori' => 'Recurve Putra',
            'jarak_meter' => 18,
            'skor' => 325,
            'peringkat' => 1,
            'medali' => 'Emas',
        ]);

        $update->assertRedirect();
        $this->assertDatabaseHas('hasil_lomba', [
            'id' => $hasil->id,
            'skor' => 325,
            'peringkat' => 1,
            'medali' => 'Emas',
        ]);
    }
}
