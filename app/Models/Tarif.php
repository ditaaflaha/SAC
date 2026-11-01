<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $table = 'tarif';

    protected $fillable = [
        'jenis',
        'nominal',
        'satuan',
        'berlaku_mulai',
    ];

    protected $casts = [
        'berlaku_mulai' => 'date',
    ];

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    /**
     * Ambil tarif aktif untuk jenis tertentu (berlaku_mulai <= hari ini, terbaru).
     */
    public static function aktif(string $jenis): ?static
    {
        return static::where('jenis', $jenis)
            ->where('berlaku_mulai', '<=', now()->toDateString())
            ->orderByDesc('berlaku_mulai')
            ->first();
    }
}
