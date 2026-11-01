@extends('layouts.admin')

@section('title', 'Manajemen Keanggotaan')

@section('content')
<div class="space-y-6" x-data="{ openTambah: {{ $errors->any() ? 'true' : 'false' }} }">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Keanggotaan</h1>
            <p class="text-sm text-slate-500">Daftarkan anggota baru dan kelola status serta akun login pemanah SAC.</p>
        </div>
        <button @click="openTambah = !openTambah" type="button"
                class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm inline-flex items-center gap-2 self-start sm:self-auto">
            <span x-text="openTambah ? '✕ Tutup Form' : '+ Daftarkan Anggota Baru'">+ Daftarkan Anggota Baru</span>
        </button>
    </div>

    {{-- Form Tambah / Daftarkan Anggota Baru oleh Admin --}}
    <div x-show="openTambah" x-transition class="bg-white rounded-2xl border border-emerald-200 p-6 shadow-sm">
        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
            <span class="text-lg">🏹</span>
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Form Pendaftaran Anggota Baru (Oleh Admin)</h2>
        </div>

        <form method="POST" action="{{ route('admin.keanggotaan.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Ahmad Fauzi" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp <span class="text-rose-500">*</span></label>
                    <input type="text" name="no_wa" value="{{ old('no_wa') }}" required placeholder="Contoh: 081234567890" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select name="jenis_kelamin" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-emerald-500">
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki (Ikhwan)</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan (Akhwat)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Keanggotaan <span class="text-rose-500">*</span></label>
                    <select name="jenis_keanggotaan" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-emerald-500">
                        <option value="reguler" {{ old('jenis_keanggotaan', 'reguler') === 'reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="private" {{ old('jenis_keanggotaan') === 'private' ? 'selected' : '' }}>Private Class</option>
                        <option value="trial" {{ old('jenis_keanggotaan') === 'trial' ? 'selected' : '' }}>Trial</option>
                        <option value="pasif" {{ old('jenis_keanggotaan') === 'pasif' ? 'selected' : '' }}>Pasif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Lahir (Opsional)</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kepemilikan Busur (Opsional)</label>
                    <input type="text" name="jenis_busur" value="{{ old('jenis_busur') }}" placeholder="Contoh: milik sendiri / standar" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Username Login (Opsional)</label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Otomatis jika dikosongkan" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 font-mono focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password Awal</label>
                    <input type="text" name="password" value="{{ old('password', 'password123') }}" class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 font-mono focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan (Opsional)</label>
                    <input type="text" name="catatan" value="{{ old('catatan') }}" placeholder="Catatan tambahan..." class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 focus:ring-emerald-500">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="openTambah = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                    ✓ Simpan & Buat Akun Login Anggota
                </button>
            </div>
        </form>
    </div>

    {{-- Kartu Hitung Status --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.keanggotaan') }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:border-slate-300 transition">
            <div class="text-xs font-semibold text-slate-400 uppercase">Semua Anggota</div>
            <div class="text-2xl font-black text-slate-800 mt-1">{{ $counts['total'] }}</div>
        </a>
        <a href="{{ route('admin.keanggotaan', ['status' => 'aktif']) }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:border-emerald-300 transition">
            <div class="text-xs font-semibold text-emerald-600 uppercase">Aktif</div>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $counts['aktif'] }}</div>
        </a>
        <a href="{{ route('admin.keanggotaan', ['status' => 'menunggu']) }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:border-indigo-300 transition">
            <div class="text-xs font-semibold text-indigo-600 uppercase">Menunggu Approval</div>
            <div class="text-2xl font-black text-indigo-600 mt-1">{{ $counts['menunggu'] }}</div>
        </a>
        <a href="{{ route('admin.keanggotaan', ['status' => 'nonaktif']) }}" class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:border-rose-300 transition">
            <div class="text-xs font-semibold text-slate-400 uppercase">Nonaktif</div>
            <div class="text-2xl font-black text-slate-500 mt-1">{{ $counts['nonaktif'] }}</div>
        </a>
    </div>

    {{-- Filter & Search Form --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin.keanggotaan') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau nomor WhatsApp..."
                       class="w-full text-sm border-slate-200 rounded-xl px-3.5 py-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div class="w-full sm:w-44">
                <select name="status" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="w-full sm:w-44">
                <select name="jenis" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Jenis</option>
                    <option value="reguler" {{ request('jenis') === 'reguler' ? 'selected' : '' }}>Reguler</option>
                    <option value="trial" {{ request('jenis') === 'trial' ? 'selected' : '' }}>Trial</option>
                    <option value="private" {{ request('jenis') === 'private' ? 'selected' : '' }}>Private</option>
                    <option value="pasif" {{ request('jenis') === 'pasif' ? 'selected' : '' }}>Pasif</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2 bg-slate-800 text-white rounded-xl text-sm font-semibold hover:bg-slate-900 transition">
                Filter
            </button>
            @if (request()->hasAny(['search', 'status', 'jenis']))
                <a href="{{ route('admin.keanggotaan') }}" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50 transition text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Tabel Anggota --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Nama & Kontak</th>
                        <th class="px-4 py-3.5">Jenis</th>
                        <th class="px-4 py-3.5">Gender / Usia</th>
                        <th class="px-4 py-3.5">Busur</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5">Akun Login</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($anggotaList as $a)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $a->nama }}</div>
                                <div class="text-xs text-slate-400">{{ $a->no_wa ?: '-' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="capitalize font-semibold text-slate-700">{{ $a->jenis_keanggotaan }}</span>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <div>{{ $a->jenis_kelamin === 'L' ? 'Ikhwan (L)' : ($a->jenis_kelamin === 'P' ? 'Akhwat (P)' : '-') }}</div>
                                <div class="text-slate-400">{{ $a->umur() ? $a->umur() . ' thn' : '-' }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <span class="{{ $a->jenis_busur ? 'text-emerald-700 font-semibold' : 'text-slate-400' }}">
                                    {{ $a->jenis_busur ?: 'Belum punya' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $badge = match ($a->status_keanggotaan) {
                                        'aktif'    => 'bg-emerald-100 text-emerald-800',
                                        'menunggu' => 'bg-indigo-100 text-indigo-800 animate-pulse',
                                        'nonaktif' => 'bg-slate-100 text-slate-600',
                                        'ditolak'  => 'bg-rose-100 text-rose-800',
                                        default    => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $badge }}">
                                    {{ ucfirst($a->status_keanggotaan) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs font-mono">
                                @if ($a->user)
                                    <div class="text-slate-700 font-semibold">{{ $a->user->username }}</div>
                                @else
                                    <span class="text-amber-600 italic">Belum dibuat</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('admin.keanggotaan.show', $a) }}" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl font-bold text-xs transition">
                                    Detail / Kelola →
                                </a>
                                <form method="POST" action="{{ route('admin.keanggotaan.destroy', $a) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus anggota {{ addslashes($a->nama) }} beserta akun login dan riwayat datanya?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl font-bold text-xs transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data anggota yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $anggotaList->links() }}
        </div>
    </div>
</div>
@endsection
