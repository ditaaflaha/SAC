<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\JadwalLatihan;
use App\Models\PengaturanAnalisis;
use App\Models\TargetLatihan;
use App\Models\Tarif;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnggotaPortalTest extends TestCase
{
    use RefreshDatabase;

    private User $anggotaUser;

    private Anggota $anggota;

    protected function setUp(): void
    {
        parent::setUp();

        PengaturanAnalisis::aktif();

        $this->anggota = Anggota::create([
            'nama' => 'Siti Pemanah',
            'no_wa' => '081987654321',
            'jenis_kelamin' => 'P',
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

    public function test_anggota_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->anggotaUser)->get(route('anggota.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Siti Pemanah');
    }

    public function test_anggota_can_submit_skor(): void
    {
        $response = $this->actingAs($this->anggotaUser)->post(route('anggota.skor.store'), [
            'tanggal' => now()->toDateString(),
            'sesi_ke' => 1,
            'jarak_meter' => 18,
            'jumlah_anak_panah' => 36,
            'total_skor' => 300,
            'catatan' => 'Latihan mandiri',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('skor_tembakan', [
            'anggota_id' => $this->anggota->id,
            'jumlah_anak_panah' => 36,
            'total_skor' => 300,
            'status_validasi' => 'menunggu',
        ]);
    }

    public function test_anggota_can_record_qty_shoot(): void
    {
        $response = $this->actingAs($this->anggotaUser)->post(route('anggota.skor.qty.store'), [
            'tanggal' => now()->toDateString(),
            'jumlah_anak_panah' => 72,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('qty_shoot', [
            'anggota_id' => $this->anggota->id,
            'jumlah_anak_panah' => 72,
        ]);
    }

    public function test_anggota_can_self_presensi_today(): void
    {
        $jadwal = JadwalLatihan::create([
            'tanggal' => now()->toDateString(),
            'waktu_mulai' => '07:00',
            'waktu_selesai' => '09:00',
            'lokasi' => 'Lapangan SAC',
            'jenis' => 'wajib',
        ]);

        $response = $this->actingAs($this->anggotaUser)->post(route('anggota.jadwal.presensi', $jadwal));
        $response->assertRedirect();

        $this->assertDatabaseHas('presensi', [
            'anggota_id' => $this->anggota->id,
            'jadwal_id' => $jadwal->id,
        ]);
    }

    public function test_anggota_can_view_progres_and_leaderboard(): void
    {
        $progres = $this->actingAs($this->anggotaUser)->get(route('anggota.progres'));
        $progres->assertStatus(200);

        $leaderboard = $this->actingAs($this->anggotaUser)->get(route('anggota.leaderboard'));
        $leaderboard->assertStatus(200);
    }

    public function test_anggota_can_download_kta_pdf(): void
    {
        $response = $this->actingAs($this->anggotaUser)->get(route('anggota.progres.kta'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_anggota_can_create_and_delete_target(): void
    {
        $create = $this->actingAs($this->anggotaUser)->post(route('anggota.target.store'), [
            'jenis_target' => 'rata_rata_skor',
            'nilai_target' => 310,
            'periode_mulai' => now()->startOfMonth()->toDateString(),
            'periode_selesai' => now()->endOfMonth()->toDateString(),
        ]);

        $create->assertRedirect();
        $target = TargetLatihan::where('anggota_id', $this->anggota->id)->first();
        $this->assertNotNull($target);

        $delete = $this->actingAs($this->anggotaUser)->delete(route('anggota.target.destroy', $target));
        $delete->assertRedirect();
        $this->assertDatabaseMissing('target_latihan', ['id' => $target->id]);
    }
}
