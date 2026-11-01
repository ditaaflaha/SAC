<?php

namespace App\Services;

use App\Models\Anggota;
use App\Models\PengaturanAnalisis;
use App\Models\Peringatan;
use App\Models\SkorTembakan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * AnalisisService — logika utama analisis perkembangan anggota SAC.
 *
 * Aturan bisnis (sesuai blueprint Bagian 4.1):
 * - Rata-rata bulanan = jumlah total_skor sesi valid ÷ jumlah sesi
 * - Status: Jarang Latihan → Belum Ada Pembanding → Naik → Turun → Tetap
 * - Tidak ada persentase, semua dalam POIN.
 */
class AnalisisService
{
    /**
     * Hitung rata-rata skor bulanan anggota.
     * Mengembalikan null jika tidak ada sesi valid bulan tersebut.
     */
    public function rataRataBulanan(int $anggotaId, Carbon $bulan): ?float
    {
        $sesi = SkorTembakan::where('anggota_id', $anggotaId)
            ->valid()
            ->bulan($bulan)
            ->get();

        if ($sesi->isEmpty()) {
            return null;
        }

        return round($sesi->avg('total_skor'), 2);
    }

    /**
     * Hitung jumlah sesi latihan valid dalam sebulan.
     */
    public function jumlahSesi(int $anggotaId, Carbon $bulan): int
    {
        return SkorTembakan::where('anggota_id', $anggotaId)
            ->valid()
            ->bulan($bulan)
            ->count();
    }

    /**
     * Tentukan status perkembangan anggota untuk bulan tertentu.
     *
     * @return array{rata_ini: ?float, rata_lalu: ?float, selisih: ?float,
     *               jumlah_sesi: int, status: string, saran: string}
     */
    public function statusAnggota(int $anggotaId, Carbon $bulan): array
    {
        // Ambil pengaturan terkini
        $cfg = PengaturanAnalisis::aktif();

        $bulanLalu = $bulan->copy()->subMonth()->startOfMonth();

        $rataIni = $this->rataRataBulanan($anggotaId, $bulan);
        $rataLalu = $this->rataRataBulanan($anggotaId, $bulanLalu);
        $jumlahSesi = $this->jumlahSesi($anggotaId, $bulan);

        // --- Pengecekan status (urutan sesuai aturan bisnis) ---

        // 1. Jarang Latihan: sesi < minimum
        if ($jumlahSesi < $cfg->min_latihan_bulanan) {
            return $this->buatHasil($rataIni, $rataLalu, $jumlahSesi, 'Jarang Latihan', $cfg->saran_jarang);
        }

        // 2. Belum ada data bulan lalu
        if ($rataLalu === null) {
            return $this->buatHasil($rataIni, $rataLalu, $jumlahSesi, 'Belum Ada Pembanding', '');
        }

        // 3. Hitung selisih
        $selisih = round($rataIni - $rataLalu, 2);

        // 4. Naik
        if ($selisih >= $cfg->batas_poin) {
            return $this->buatHasil($rataIni, $rataLalu, $jumlahSesi, 'Naik', $cfg->saran_naik, $selisih);
        }

        // 5. Turun
        if ($selisih <= -$cfg->batas_poin) {
            return $this->buatHasil($rataIni, $rataLalu, $jumlahSesi, 'Turun', $cfg->saran_turun, $selisih);
        }

        // 6. Tetap
        return $this->buatHasil($rataIni, $rataLalu, $jumlahSesi, 'Tetap', $cfg->saran_tetap, $selisih);
    }

    /**
     * Rekap seluruh komunitas untuk satu bulan.
     * Otomatis membuat/update peringatan untuk Tetap/Turun/Jarang Latihan.
     *
     * @return Collection<int, array>
     */
    public function rekapKomunitas(Carbon $bulan): Collection
    {
        $anggotaList = Anggota::where('status_keanggotaan', 'aktif')->get();

        return $anggotaList->map(function (Anggota $anggota) use ($bulan) {
            $hasil = $this->statusAnggota($anggota->id, $bulan);
            $hasil['anggota'] = $anggota;

            // Buat peringatan otomatis untuk status yang membutuhkan perhatian
            if (in_array($hasil['status'], ['Tetap', 'Turun', 'Jarang Latihan'])) {
                Peringatan::updateOrCreate(
                    [
                        'anggota_id' => $anggota->id,
                        'bulan' => $bulan->toDateString(),
                    ],
                    [
                        'status' => $hasil['status'],
                        'rekomendasi' => $hasil['saran'],
                        'sudah_ditindaklanjuti' => false,
                    ]
                );
            }

            return $hasil;
        });
    }

    /**
     * Helper — susun array hasil analisis.
     */
    private function buatHasil(
        ?float $rataIni,
        ?float $rataLalu,
        int $jumlahSesi,
        string $status,
        string $saran,
        ?float $selisih = null
    ): array {
        return [
            'rata_ini' => $rataIni,
            'rata_lalu' => $rataLalu,
            'selisih' => $selisih ?? ($rataIni !== null && $rataLalu !== null ? round($rataIni - $rataLalu, 2) : null),
            'jumlah_sesi' => $jumlahSesi,
            'status' => $status,
            'saran' => $saran,
        ];
    }
}
