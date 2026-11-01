<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilLomba extends Model
{
    use HasFactory;

    protected $table = 'hasil_lomba';

    protected $fillable = [
        'anggota_id',
        'kejuaraan_id',
        'nama_tim',
        'kategori',
        'jarak_meter',
        'skor',
        'peringkat',
        'medali',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function kejuaraan()
    {
        return $this->belongsTo(Kejuaraan::class);
    }
}
