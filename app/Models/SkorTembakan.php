<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkorTembakan extends Model
{
    use HasFactory;

    protected $table = 'skor_tembakan';

    protected $fillable = [
        'anggota_id',
        'tanggal',
        'sesi_ke',
        'jarak_meter',
        'jumlah_anak_panah',
        'jumlah_end',
        'anak_panah_per_end',
        'total_skor',
        'status_validasi',
        'divalidasi_oleh',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function getQtyPanahAttribute(): int
    {
        if (! empty($this->jumlah_anak_panah)) {
            return (int) $this->jumlah_anak_panah;
        }

        return (int) (($this->jumlah_end ?? 0) * ($this->anak_panah_per_end ?? 0));
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'divalidasi_oleh');
    }

    /** Scope: hanya skor yang sudah valid */
    public function scopeValid($query)
    {
        return $query->where('status_validasi', 'valid');
    }

    /** Scope: filter bulan tertentu */
    public function scopeBulan($query, Carbon $bulan)
    {
        return $query->whereYear('tanggal', $bulan->year)
            ->whereMonth('tanggal', $bulan->month);
    }
}
