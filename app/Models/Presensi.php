<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'anggota_id',
        'jadwal_id',
        'waktu_hadir',
    ];

    protected $casts = [
        'waktu_hadir' => 'datetime',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalLatihan::class, 'jadwal_id');
    }

    public function jadwalLatihan()
    {
        return $this->belongsTo(JadwalLatihan::class, 'jadwal_id');
    }

    public function scopeBulan($query, Carbon $bulan)
    {
        return $query->whereHas('jadwal', fn ($q) => $q->whereYear('tanggal', $bulan->year)
            ->whereMonth('tanggal', $bulan->month)
        );
    }
}
