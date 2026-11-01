@extends('layouts.admin')

@section('title', 'Presensi Latihan — ' . $jadwal->tanggal->translatedFormat('d M Y'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.jadwal') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            ← Kembali ke Daftar Jadwal
        </a>
    </div>

    {{-- Detail Jadwal Banner --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $jadwal->jenis === 'wajib' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-700' }}">
                Latihan {{ ucfirst($jadwal->jenis) }}
            </span>
            <h1 class="text-xl font-black text-slate-800 mt-2">
                {{ $jadwal->tanggal->translatedFormat('l, d F Y') }}
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                ⏰ {{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }} WIB · 📍 {{ $jadwal->lokasi }}
            </p>
        </div>
        <div class="text-right">
            <div class="text-3xl font-black text-emerald-600 font-mono">{{ count($hadirIds) }}</div>
            <div class="text-xs text-slate-400">Total Anggota Hadir</div>
        </div>
    </div>

    {{-- Daftar Checklist Kehadiran Anggota --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-800">Checklist Presensi Anggota Aktif</h2>
            <p class="text-xs text-slate-400">Klik tombol di kolom kehadiran untuk menandai hadir atau membatalkan.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Nama Anggota</th>
                        <th class="px-4 py-3.5">Gender</th>
                        <th class="px-4 py-3.5">Jenis Anggota</th>
                        <th class="px-4 py-3.5 text-center">Status Presensi</th>
                        <th class="px-6 py-3.5 text-right">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($anggotaAktif as $a)
                        @php
                            $isHadir = isset($hadirIds[$a->id]);
                            $waktuHadir = $isHadir ? $jadwal->presensi->firstWhere('anggota_id', $a->id)?->waktu_hadir : null;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition {{ $isHadir ? 'bg-emerald-50/20' : '' }}">
                            <td class="px-6 py-3.5">
                                <div class="font-bold text-slate-800">{{ $a->nama }}</div>
                                <div class="text-xs text-slate-400">{{ $a->no_wa ?: '-' }}</div>
                            </td>
                            <td class="px-4 py-3.5 text-xs text-slate-600">
                                {{ $a->jenis_kelamin === 'L' ? 'Ikhwan (L)' : 'Akhwat (P)' }}
                            </td>
                            <td class="px-4 py-3.5 text-xs capitalize font-semibold text-slate-700">
                                {{ $a->jenis_keanggotaan }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @if ($isHadir)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-xs">
                                        ✓ Hadir {{ $waktuHadir ? '(' . $waktuHadir->format('H:i') . ')' : '' }}
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-slate-100 text-slate-400 rounded-full font-semibold text-xs">
                                        Tidak Hadir
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <form method="POST" action="{{ route('admin.jadwal.presensi.toggle', $jadwal) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="anggota_id" value="{{ $a->id }}">
                                    @if ($isHadir)
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-xs font-bold transition">
                                            Batalkan Hadir
                                        </button>
                                    @else
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white hover:bg-emerald-700 rounded-xl text-xs font-bold transition shadow-sm">
                                            + Tandai Hadir
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                Belum ada anggota aktif terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
