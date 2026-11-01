<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanAnalisis extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_analisis';

    protected $fillable = [
        'user_id',
        'batas_poin',
        'min_latihan_bulanan',
        'anak_panah_per_sesi',
        'saran_naik',
        'saran_tetap',
        'saran_turun',
        'saran_jarang',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Ambil satu-satunya row pengaturan, atau buat default jika belum ada.
     */
    public static function aktif(): static
    {
        return static::firstOrCreate([], [
            'batas_poin' => 5,
            'min_latihan_bulanan' => 4,
            'anak_panah_per_sesi' => 36,
            'saran_naik' => 'Pertahankan ritme ini dan naikkan target kamu!',
            'saran_tetap' => 'Coba variasi jarak atau latihan teknik, dan konsultasi dengan pelatih.',
            'saran_turun' => 'Evaluasi teknik, istirahat yang cukup, dan konsultasi dengan pelatih.',
            'saran_jarang' => 'Tambah frekuensi latihan (minimal 2 kali seminggu).',
        ]);
    }
}
