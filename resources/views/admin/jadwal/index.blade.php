@extends('layouts.admin')

@section('title', 'Jadwal & Presensi Latihan')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Jadwal & Presensi Latihan</h1>
            <p class="text-sm text-slate-500">Atur agenda latihan rutin, mandiri, dan kelola absensi kehadiran pemanah.</p>
        </div>
    </div>

    {{-- Form Tambah Jadwal Baru --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Tambah Jadwal Latihan Baru</h2>
        <form method="POST" action="{{ route('admin.jadwal.store') }}" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                <input type="date" name="tanggal" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu Mulai</label>
                <input type="time" name="waktu_mulai" value="07:00" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu Selesai</label>
                <input type="time" name="waktu_selesai" value="09:00" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Latihan</label>
                <select name="jenis" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                    <option value="wajib">Wajib Mingguan</option>
                    <option value="mandiri">Latihan Mandiri</option>
                </select>
            </div>

            <div class="lg:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Lokasi Latihan</label>
                <input type="text" name="lokasi" value="Lapangan Archery SAC, Batam" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
            </div>

            <div class="sm:col-span-3 lg:col-span-5">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Agenda Tambahan</label>
                <input type="text" name="keterangan" placeholder="Contoh: Latihan teknik scoring 18m & drill form..." class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
            </div>

            <div class="sm:col-span-3 lg:col-span-1 flex items-end">
                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-sm">
                    + Jadwalkan
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Jadwal --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">Daftar Jadwal Latihan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Tanggal</th>
                        <th class="px-4 py-3.5">Waktu</th>
                        <th class="px-4 py-3.5">Jenis</th>
                        <th class="px-6 py-3.5">Lokasi & Keterangan</th>
                        <th class="px-4 py-3.5 text-center">Kehadiran</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($jadwalList as $j)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $j->tanggal->translatedFormat('l, d F Y') }}</div>
                                @if ($j->isHariIni())
                                    <span class="inline-block mt-0.5 px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded font-extrabold text-[10px]">
                                        HARI INI
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs font-mono font-semibold text-slate-700">
                                {{ substr($j->waktu_mulai, 0, 5) }} - {{ substr($j->waktu_selesai, 0, 5) }} WIB
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $j->jenis === 'wajib' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($j->jenis) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div class="font-semibold text-slate-800">{{ $j->lokasi }}</div>
                                <div class="text-slate-400 mt-0.5">{{ $j->keterangan ?: '-' }}</div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 font-extrabold text-xs rounded-xl">
                                    {{ $j->presensi_count }} Hadir
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.jadwal.presensi', $j) }}" class="inline-flex items-center px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition">
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
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Belum ada jadwal latihan yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $jadwalList->links() }}
        </div>
    </div>
</div>
@endsection
