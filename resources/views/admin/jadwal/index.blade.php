@extends('layouts.admin')

@section('title', 'Jadwal & Presensi Latihan')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Jadwal & Presensi Latihan</h1>
            <p class="text-sm text-[#6B7280]">Atur agenda latihan rutin, mandiri, dan kelola absensi kehadiran pemanah.</p>
        </div>
    </div>

    {{-- Form Tambah Jadwal Baru --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm">
        <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider mb-4">Tambah Jadwal Latihan Baru</h2>
        <form method="POST" action="{{ route('admin.jadwal.store') }}" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-[#1F2937] mb-1">Tanggal</label>
                <input type="date" name="tanggal" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1F2937] mb-1">Waktu Mulai</label>
                <input type="time" name="waktu_mulai" value="07:00" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1F2937] mb-1">Waktu Selesai</label>
                <input type="time" name="waktu_selesai" value="09:00" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#1F2937] mb-1">Jenis Latihan</label>
                <select name="jenis" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="wajib">Wajib Mingguan</option>
                    <option value="mandiri">Latihan Mandiri</option>
                </select>
            </div>

            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-[#1F2937] mb-1">Lokasi Latihan</label>
                <input type="text" name="lokasi" value="Lapangan Archery SAC, Batam" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div class="sm:col-span-3 lg:col-span-5">
                <label class="block text-xs font-semibold text-[#1F2937] mb-1">Keterangan / Agenda Tambahan</label>
                <input type="text" name="keterangan" placeholder="Contoh: Latihan teknik scoring 18m & drill form..." class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div class="sm:col-span-3 lg:col-span-1 flex items-end">
                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold text-xs rounded-xl transition shadow-sm">
                    + Jadwalkan
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Jadwal --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-[#E5E7EB] flex items-center justify-between">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Daftar Jadwal Latihan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[#6B7280]">
                <thead class="bg-[#FAFAFB] text-xs uppercase font-semibold text-[#6B7280] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="px-6 py-3.5">Tanggal</th>
                        <th class="px-4 py-3.5">Waktu</th>
                        <th class="px-4 py-3.5">Jenis</th>
                        <th class="px-6 py-3.5">Lokasi & Keterangan</th>
                        <th class="px-4 py-3.5 text-center">Kehadiran</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse ($jadwalList as $j)
                        <tr class="hover:bg-primary-50/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#1F2937]">{{ $j->tanggal->translatedFormat('l, d F Y') }}</div>
                                @if ($j->isHariIni())
                                    <span class="inline-block mt-0.5 px-2 py-0.5 bg-primary-100 text-primary-700 rounded font-bold text-[10px]">
                                        HARI INI
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs font-mono font-semibold text-[#1F2937]">
                                {{ substr($j->waktu_mulai, 0, 5) }} - {{ substr($j->waktu_selesai, 0, 5) }} WIB
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $j->jenis === 'wajib' ? 'bg-primary-50 text-primary-700' : 'bg-sky-50 text-sky-700' }}">
                                    {{ ucfirst($j->jenis) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div class="font-semibold text-[#1F2937]">{{ $j->lokasi }}</div>
                                <div class="text-[#6B7280] mt-0.5">{{ $j->keterangan ?: '-' }}</div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 font-bold text-xs rounded-xl">
                                    {{ $j->presensi_count }} Hadir
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.jadwal.presensi', $j) }}" class="inline-flex items-center px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                                    Presensi ({{ $j->presensi_count }}) →
                                </a>
                                <form method="POST" action="{{ route('admin.jadwal.destroy', $j) }}" class="inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition text-xs">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#6B7280]">
                                Belum ada jadwal latihan yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#E5E7EB]">
            {{ $jadwalList->links() }}
        </div>
    </div>
</div>
@endsection
