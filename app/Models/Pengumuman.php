<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'tanggal',
        'publik',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'publik' => 'boolean',
    ];

    public function penulis()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublik($query)
    {
        return $query->where('publik', true);
    }
}
