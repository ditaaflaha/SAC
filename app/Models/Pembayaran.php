<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'anggota_id',
        'tarif_id',
        'nama_peserta',
        'tanggal_bayar',
        'periode',
        'cicilan_ke',
        'jumlah',
        'bukti_transfer',
        'status',
        'alasan_tolak',
        'diverifikasi_oleh',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'periode' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function scopeBulan($query, Carbon $bulan)
    {
        return $query->whereYear('periode', $bulan->year)
            ->whereMonth('periode', $bulan->month);
    }

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }
}
