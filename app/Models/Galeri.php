<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';

    protected $fillable = [
        'user_id',
        'judul',
        'file',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pengunggah()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
