@extends('layouts.admin')

@section('title', 'Manajemen Keanggotaan')

@section('content')
<div class="space-y-6" x-data="{ openTambah: {{ $errors->any() ? 'true' : 'false' }} }">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Manajemen Keanggotaan</h1>
            <p class="text-sm text-[#6B7280]">Daftarkan anggota baru dan kelola status serta akun login pemanah SAC.</p>
        </div>
        <button @click="openTambah = !openTambah" type="button"
                class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition shadow-sm inline-flex items-center gap-2 self-start sm:self-auto">
            <span x-text="openTambah ? '✕ Tutup Form' : '+ Daftarkan Anggota Baru'">+ Daftarkan Anggota Baru</span>
        </button>
    </div>

    {{-- Form Tambah / Daftarkan Anggota Baru oleh Admin --}}
    <div x-show="openTambah" x-transition class="bg-white rounded-2xl border border-primary-200 p-6 shadow-sm">
        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-[#E5E7EB]">
            <span class="text-lg">🏹</span>
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Form Pendaftaran Anggota Baru (Oleh Admin)</h2>
        </div>

        <form method="POST" action="{{ route('admin.keanggotaan.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Ahmad Fauzi" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3.5 py-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">No. WhatsApp <span class="text-rose-500">*</span></label>
                    <input type="text" name="no_wa" value="{{ old('no_wa') }}" required placeholder="Contoh: 081234567890" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3.5 py-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select name="jenis_kelamin" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki (Ikhwan)</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan (Akhwat)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Jenis Keanggotaan <span class="text-rose-500">*</span></label>
                    <select name="jenis_keanggotaan" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="reguler" {{ old('jenis_keanggotaan', 'reguler') === 'reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="private" {{ old('jenis_keanggotaan') === 'private' ? 'selected' : '' }}>Private Class</option>
                        <option value="trial" {{ old('jenis_keanggotaan') === 'trial' ? 'selected' : '' }}>Trial</option>
                        <option value="pasif" {{ old('jenis_keanggotaan') === 'pasif' ? 'selected' : '' }}>Pasif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Tanggal Lahir (Opsional)</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3.5 py-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Kepemilikan Busur (Opsional)</label>
                    <input type="text" name="jenis_busur" value="{{ old('jenis_busur') }}" placeholder="Contoh: milik sendiri / standar" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3.5 py-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-[#E5E7EB]">
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Username Login (Opsional)</label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Otomatis jika dikosongkan" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3.5 py-2 font-mono focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Password Awal</label>
                    <input type="text" name="password" value="{{ old('password', 'password123') }}" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3.5 py-2 font-mono focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Catatan (Opsional)</label>
                    <input type="text" name="catatan" value="{{ old('catatan') }}" placeholder="Catatan tambahan..." class="w-full text-sm border-[#E5E7EB] rounded-xl px-3.5 py-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" @click="openTambah = false" class="px-4 py-2 bg-primary-50 text-primary-700 rounded-xl text-xs font-semibold hover:bg-primary-100 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                    ✓ Simpan & Buat Akun Login Anggota
                </button>
            </div>
        </form>
    </div>

    {{-- Kartu Hitung Status --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.keanggotaan') }}" class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-sm hover:border-primary-300 transition">
            <div class="text-xs font-semibold text-[#6B7280] uppercase">Semua Anggota</div>
            <div class="text-2xl font-extrabold text-[#1F2937] mt-1">{{ $counts['total'] }}</div>
        </a>
        <a href="{{ route('admin.keanggotaan', ['status' => 'aktif']) }}" class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-sm hover:border-emerald-300 transition">
            <div class="text-xs font-semibold text-emerald-600 uppercase">Aktif</div>
            <div class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $counts['aktif'] }}</div>
        </a>
        <a href="{{ route('admin.keanggotaan', ['status' => 'menunggu']) }}" class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-sm hover:border-primary-300 transition">
            <div class="text-xs font-semibold text-primary-600 uppercase">Menunggu Approval</div>
            <div class="text-2xl font-extrabold text-primary-600 mt-1">{{ $counts['menunggu'] }}</div>
        </a>
        <a href="{{ route('admin.keanggotaan', ['status' => 'nonaktif']) }}" class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-sm hover:border-rose-300 transition">
            <div class="text-xs font-semibold text-[#6B7280] uppercase">Nonaktif</div>
            <div class="text-2xl font-extrabold text-[#6B7280] mt-1">{{ $counts['nonaktif'] }}</div>
        </a>
    </div>

    {{-- Filter & Search Form --}}
    <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-sm">
        <form method="GET" action="{{ route('admin.keanggotaan') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau nomor WhatsApp..."
                       class="w-full text-sm border-[#E5E7EB] rounded-xl px-3.5 py-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="w-full sm:w-44">
                <select name="status" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="w-full sm:w-44">
                <select name="jenis" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Jenis</option>
                    <option value="reguler" {{ request('jenis') === 'reguler' ? 'selected' : '' }}>Reguler</option>
                    <option value="trial" {{ request('jenis') === 'trial' ? 'selected' : '' }}>Trial</option>
                    <option value="private" {{ request('jenis') === 'private' ? 'selected' : '' }}>Private</option>
                    <option value="pasif" {{ request('jenis') === 'pasif' ? 'selected' : '' }}>Pasif</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2 bg-primary-600 text-white rounded-xl text-sm font-semibold hover:bg-primary-700 transition shadow-sm">
                Filter
            </button>
            @if (request()->hasAny(['search', 'status', 'jenis']))
                <a href="{{ route('admin.keanggotaan') }}" class="px-4 py-2 bg-primary-50 text-primary-700 rounded-xl text-sm font-semibold hover:bg-primary-100 transition text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Tabel Anggota --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-[#6B7280]">
                <thead class="bg-[#FAFAFB] text-xs uppercase font-semibold text-[#6B7280] border-b border-[#E5E7EB]">
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
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse ($anggotaList as $a)
                        <tr class="hover:bg-primary-50/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#1F2937]">{{ $a->nama }}</div>
                                <div class="text-xs text-[#6B7280]">{{ $a->no_wa ?: '-' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="capitalize font-semibold text-[#1F2937]">{{ $a->jenis_keanggotaan }}</span>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <div>{{ $a->jenis_kelamin === 'L' ? 'Ikhwan (L)' : ($a->jenis_kelamin === 'P' ? 'Akhwat (P)' : '-') }}</div>
                                <div class="text-[#6B7280]">{{ $a->umur() ? $a->umur() . ' thn' : '-' }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <span class="{{ $a->jenis_busur ? 'text-primary-700 font-semibold' : 'text-[#6B7280]' }}">
                                    {{ $a->jenis_busur ?: 'Belum punya' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $badge = match ($a->status_keanggotaan) {
                                        'aktif'    => 'bg-emerald-50 text-emerald-700',
                                        'menunggu' => 'bg-primary-100 text-primary-700',
                                        'nonaktif' => 'bg-gray-100 text-[#6B7280]',
                                        'ditolak'  => 'bg-rose-50 text-rose-700',
                                        default    => 'bg-gray-100 text-[#6B7280]',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $badge }}">
                                    {{ ucfirst($a->status_keanggotaan) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs font-mono">
                                @if ($a->user)
                                    <div class="text-[#1F2937] font-semibold">{{ $a->user->username }}</div>
                                @else
                                    <span class="text-amber-600 italic">Belum dibuat</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('admin.keanggotaan.show', $a) }}" class="inline-flex items-center px-3 py-1.5 bg-primary-50 text-primary-700 hover:bg-primary-100 rounded-xl font-semibold text-xs transition">
                                    Detail / Kelola →
                                </a>
                                <form method="POST" action="{{ route('admin.keanggotaan.destroy', $a) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus anggota {{ addslashes($a->nama) }} beserta akun login dan riwayat datanya?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl font-semibold text-xs transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-[#6B7280]">
                                Tidak ada data anggota yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-[#E5E7EB]">
            {{ $anggotaList->links() }}
        </div>
    </div>
</div>
@endsection
