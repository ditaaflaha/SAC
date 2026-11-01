<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QtyShoot extends Model
{
    use HasFactory;

    protected $table = 'qty_shoot';

    protected $fillable = [
        'anggota_id',
        'tanggal',
        'jumlah_anak_panah',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function scopeBulan($query, Carbon $bulan)
    {
        return $query->whereYear('tanggal', $bulan->year)
            ->whereMonth('tanggal', $bulan->month);
    }
}
