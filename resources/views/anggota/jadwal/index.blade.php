@extends('layouts.anggota')

@section('title', 'Jadwal & Presensi Latihan')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Jadwal & Presensi Latihan</h1>
        <p class="text-sm text-[#6B7280]">Lihat agenda latihan mendatang dan lakukan presensi kehadiran mandiri.</p>
    </div>

    {{-- Jadwal Mendatang --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6 space-y-4">
        <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Jadwal Latihan Mendatang</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse ($jadwalMendatang as $j)
                @php
                    $isHadir = isset($presensiSaya[$j->id]);
                    $hariIni = $j->isHariIni();
                @endphp
                <div class="p-5 rounded-2xl border {{ $hariIni ? 'border-primary-300 bg-primary-50/40' : 'border-[#E5E7EB] bg-[#FAFAFB]' }} flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $j->jenis === 'wajib' ? 'bg-primary-100 text-primary-700' : 'bg-sky-50 text-sky-700' }}">
                                Latihan {{ ucfirst($j->jenis) }}
                            </span>
                            @if ($hariIni)
                                <span class="px-2 py-0.5 bg-primary-600 text-white text-[10px] font-bold rounded-full">
                                    HARI INI
                                </span>
                            @endif
                        </div>
                        <h3 class="font-bold text-base text-[#1F2937]">{{ $j->tanggal->translatedFormat('l, d F Y') }}</h3>
                        <p class="text-xs text-[#6B7280] mt-1">
                            ⏰ {{ substr($j->waktu_mulai, 0, 5) }} - {{ substr($j->waktu_selesai, 0, 5) }} WIB
                        </p>
                        <p class="text-xs text-[#6B7280] mt-1">
                            📍 {{ $j->lokasi }}
                        </p>
                        @if ($j->keterangan)
                            <p class="text-xs text-[#6B7280] mt-2 bg-white p-2 rounded-xl border border-[#E5E7EB]">
                                💬 {{ $j->keterangan }}
                            </p>
                        @endif
                    </div>

                    <div>
                        @if ($isHadir)
                            <div class="w-full py-2 bg-emerald-50 text-emerald-700 rounded-xl text-xs font-bold text-center border border-emerald-200">
                                ✓ Sudah Presensi Hadir
                            </div>
                        @elseif ($hariIni)
                            <form method="POST" action="{{ route('anggota.jadwal.presensi', $j) }}">
                                @csrf
                                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                                    🎯 Presensi Hadir Sekarang
                                </button>
                            </form>
                        @else
                            <div class="w-full py-2 bg-gray-100 text-[#6B7280] rounded-xl text-xs font-medium text-center">
                                Presensi dibuka saat hari latihan
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-xs text-[#6B7280] py-6 text-center col-span-full">Belum ada jadwal latihan mendatang.</p>
            @endforelse
        </div>
    </div>

    {{-- Riwayat Jadwal Lampau --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-[#E5E7EB]">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Riwayat Kehadiran Latihan Lalu</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[#6B7280]">
                <thead class="bg-[#FAFAFB] text-xs uppercase font-semibold text-[#6B7280] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-3">Tanggal Latihan</th>
                        <th class="px-4 py-3">Jenis</th>
                        <th class="px-6 py-3">Lokasi</th>
                        <th class="px-6 py-3 text-right">Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse ($jadwalLampau as $jl)
                        @php $hadir = isset($presensiSaya[$jl->id]); @endphp
                        <tr class="hover:bg-primary-50/30 transition">
                            <td class="px-6 py-3 font-semibold text-[#1F2937] text-xs">
                                {{ $jl->tanggal->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 py-3 text-xs capitalize">
                                {{ $jl->jenis }}
                            </td>
                            <td class="px-6 py-3 text-xs text-[#6B7280]">
                                {{ $jl->lokasi }}
                            </td>
                            <td class="px-6 py-3 text-right">
                                @if ($hadir)
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full font-bold text-xs">
                                        ✓ Hadir
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-gray-100 text-[#6B7280] rounded-full font-semibold text-xs">
                                        Tidak Hadir
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-[#6B7280] text-xs">
                                Belum ada riwayat jadwal latihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#E5E7EB]">
            {{ $jadwalLampau->links() }}
        </div>
    </div>
</div>
@endsection
