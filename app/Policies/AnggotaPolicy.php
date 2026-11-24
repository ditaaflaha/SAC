<?php

namespace App\Policies;

use App\Models\Anggota;
use App\Models\User;

class AnggotaPolicy
{
    /**
     * Tentukan apakah user boleh mengunduh laporan perkembangan PDF anggota tertentu.
     * Admin boleh mengunduh laporan semua anggota; anggota hanya miliknya sendiri.
     */
    public function downloadLaporan(User $user, Anggota $anggota): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return (int) $user->anggota_id === (int) $anggota->id;
    }
}

