@extends('layouts.admin')

@section('title', 'Presensi Latihan — ' . $jadwal->tanggal->translatedFormat('d M Y'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.jadwal') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition">
            ← Kembali ke Daftar Jadwal
        </a>
    </div>

    {{-- Detail Jadwal Banner --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $jadwal->jenis === 'wajib' ? 'bg-primary-50 text-primary-700' : 'bg-sky-50 text-sky-700' }}">
                Latihan {{ ucfirst($jadwal->jenis) }}
            </span>
            <h1 class="text-xl font-bold text-[#1F2937] mt-2">
                {{ $jadwal->tanggal->translatedFormat('l, d F Y') }}
            </h1>
            <p class="text-xs text-[#6B7280] mt-1">
                ⏰ {{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }} WIB · 📍 {{ $jadwal->lokasi }}
            </p>
        </div>
        <div class="text-right">
            <div class="text-3xl font-extrabold text-primary-600 font-mono">{{ count($hadirIds) }}</div>
            <div class="text-xs text-[#6B7280]">Total Anggota Hadir</div>
        </div>
    </div>

    {{-- Daftar Checklist Kehadiran Anggota --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-[#E5E7EB]">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Checklist Presensi Anggota Aktif</h2>
            <p class="text-xs text-[#6B7280] mt-0.5">Klik tombol di kolom kehadiran untuk menandai hadir atau membatalkan.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[#6B7280]">
                <thead class="bg-[#FAFAFB] text-xs uppercase font-semibold text-[#6B7280] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-3.5">Nama Anggota</th>
                        <th class="px-4 py-3.5">Gender</th>
                        <th class="px-4 py-3.5">Jenis Anggota</th>
                        <th class="px-4 py-3.5 text-center">Status Presensi</th>
                        <th class="px-6 py-3.5 text-right">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse ($anggotaAktif as $a)
                        @php
                            $isHadir = isset($hadirIds[$a->id]);
                            $waktuHadir = $isHadir ? $jadwal->presensi->firstWhere('anggota_id', $a->id)?->waktu_hadir : null;
                        @endphp
                        <tr class="hover:bg-primary-50/30 transition {{ $isHadir ? 'bg-primary-50/20' : '' }}">
                            <td class="px-6 py-3.5">
                                <div class="font-bold text-[#1F2937]">{{ $a->nama }}</div>
                                <div class="text-xs text-[#6B7280]">{{ $a->no_wa ?: '-' }}</div>
                            </td>
                            <td class="px-4 py-3.5 text-xs text-[#6B7280]">
                                {{ $a->jenis_kelamin === 'L' ? 'Ikhwan (L)' : 'Akhwat (P)' }}
                            </td>
                            <td class="px-4 py-3.5 text-xs capitalize font-semibold text-[#1F2937]">
                                {{ $a->jenis_keanggotaan }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @if ($isHadir)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full font-bold text-xs">
                                        ✓ Hadir {{ $waktuHadir ? '(' . $waktuHadir->format('H:i') . ')' : '' }}
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-gray-100 text-[#6B7280] rounded-full font-semibold text-xs">
                                        Tidak Hadir
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <form method="POST" action="{{ route('admin.jadwal.presensi.toggle', $jadwal) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="anggota_id" value="{{ $a->id }}">
                                    @if ($isHadir)
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-xs font-semibold transition">
                                            Batalkan Hadir
                                        </button>
                                    @else
                                        <button type="submit" class="px-3 py-1.5 bg-primary-600 text-white hover:bg-primary-700 rounded-xl text-xs font-semibold transition shadow-sm">
                                            + Tandai Hadir
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[#6B7280]">
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
