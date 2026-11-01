<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetLatihan extends Model
{
    use HasFactory;

    protected $table = 'target_latihan';

    protected $fillable = [
        'anggota_id',
        'jenis_target',
        'nilai_target',
        'periode_mulai',
        'periode_selesai',
        'status',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function scopeBerjalan($query)
    {
        return $query->where('status', 'berjalan');
    }
}
