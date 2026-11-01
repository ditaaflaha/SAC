<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalLatihan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_latihan';

    protected $fillable = [
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'jenis',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'jadwal_id');
    }

    /** Cek apakah jadwal ini hari ini */
    public function isHariIni(): bool
    {
        return $this->tanggal->isToday();
    }
}
