<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peringatan extends Model
{
    use HasFactory;

    protected $table = 'peringatan';

    protected $fillable = [
        'anggota_id',
        'bulan',
        'status',
        'rekomendasi',
        'sudah_ditindaklanjuti',
    ];

    protected $casts = [
        'bulan' => 'date',
        'sudah_ditindaklanjuti' => 'boolean',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
