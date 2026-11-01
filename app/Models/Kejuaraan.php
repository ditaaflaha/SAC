<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kejuaraan extends Model
{
    use HasFactory;

    protected $table = 'kejuaraan';

    protected $fillable = [
        'nama_kejuaraan',
        'tanggal',
        'lokasi',
        'tingkat',
        'link_ianseo',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function hasilLomba()
    {
        return $this->hasMany(HasilLomba::class);
    }
}
