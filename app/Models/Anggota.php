<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'nama',
        'no_wa',
        'jenis_kelamin',
        'tanggal_lahir',
        'jenis_busur',
        'jenis_keanggotaan',
        'status_keanggotaan',
        'tanggal_gabung',
        'foto',
        'catatan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_gabung' => 'date',
    ];

    // ----------------------------------------------------------------
    // Relasi
    // ----------------------------------------------------------------

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function skorTembakan()
    {
        return $this->hasMany(SkorTembakan::class);
    }

    public function qtyShoot()
    {
        return $this->hasMany(QtyShoot::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function hasilLomba()
    {
        return $this->hasMany(HasilLomba::class);
    }

    public function peringatan()
    {
        return $this->hasMany(Peringatan::class);
    }

    public function targetLatihan()
    {
        return $this->hasMany(TargetLatihan::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    // ----------------------------------------------------------------
    // Helper
    // ----------------------------------------------------------------

    /** Cek apakah anggota masih dalam 2 bulan gratis iuran sejak tanggal_gabung */
    public function dalamPeriodeFree(): bool
    {
        if (! $this->tanggal_gabung) {
            return false;
        }

        return now()->lessThan($this->tanggal_gabung->copy()->addMonths(2)->endOfMonth());
    }

    public function umur(): ?int
    {
        return $this->tanggal_lahir?->age;
    }
}
