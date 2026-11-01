@extends('layouts.admin')

@section('title', 'Detail Anggota — ' . $anggota->nama)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.keanggotaan') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            ← Kembali ke Daftar Anggota
        </a>
        <form method="POST" action="{{ route('admin.keanggotaan.destroy', $anggota) }}" onsubmit="return confirm('Yakin ingin menghapus anggota {{ addslashes($anggota->nama) }} beserta akun login dan seluruh riwayatnya?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5">
                <span>🗑️</span> Hapus Anggota
            </button>
        </form>
    </div>

    {{-- Kartu Profil & Status --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Biodata Singkat --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm space-y-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-extrabold text-2xl flex items-center justify-center shadow-md">
                    {{ strtoupper(substr($anggota->nama, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-800">{{ $anggota->nama }}</h1>
                    <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-bold rounded-full bg-slate-100 text-slate-700 capitalize">
                        Anggota {{ $anggota->jenis_keanggotaan }}
                    </span>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4 space-y-2 text-xs">
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">WhatsApp</span>
                    <span class="font-semibold text-slate-700">{{ $anggota->no_wa ?: '-' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Gender</span>
                    <span class="font-semibold text-slate-700">{{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki (Ikhwan)' : 'Perempuan (Akhwat)' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Usia</span>
                    <span class="font-semibold text-slate-700">{{ $anggota->umur() ? $anggota->umur() . ' tahun' : '-' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Busur Pribadi</span>
                    <span class="font-semibold text-slate-700">{{ $anggota->jenis_busur ?: 'Belum punya' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Tanggal Gabung</span>
                    <span class="font-semibold text-slate-700">{{ $anggota->tanggal_gabung ? $anggota->tanggal_gabung->translatedFormat('d M Y') : '-' }}</span>
                </div>
            </div>

            @if ($anggota->catatan)
                <div class="border-t border-slate-100 pt-3">
                    <span class="text-xs text-slate-400 block mb-1">Catatan Admin:</span>
                    <p class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl">{{ $anggota->catatan }}</p>
                </div>
            @endif
        </div>

        {{-- Form Ubah Status & Jenis Keanggotaan --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Ubah Status & Jenis</h2>
            <form method="POST" action="{{ route('admin.keanggotaan.status', $anggota) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status Keanggotaan</label>
                    <select name="status_keanggotaan" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                        <option value="menunggu" {{ $anggota->status_keanggotaan === 'menunggu' ? 'selected' : '' }}>Menunggu Approval</option>
                        <option value="aktif" {{ $anggota->status_keanggotaan === 'aktif' ? 'selected' : '' }}>Aktif (Disetujui)</option>
                        <option value="nonaktif" {{ $anggota->status_keanggotaan === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="ditolak" {{ $anggota->status_keanggotaan === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Keanggotaan</label>
                    <select name="jenis_keanggotaan" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                        <option value="reguler" {{ $anggota->jenis_keanggotaan === 'reguler' ? 'selected' : '' }}>Reguler (Member Penuh)</option>
                        <option value="trial" {{ $anggota->jenis_keanggotaan === 'trial' ? 'selected' : '' }}>Trial (Uji Coba)</option>
                        <option value="private" {{ $anggota->jenis_keanggotaan === 'private' ? 'selected' : '' }}>Private Class</option>
                        <option value="pasif" {{ $anggota->jenis_keanggotaan === 'pasif' ? 'selected' : '' }}>Pasif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan Tambahan</label>
                    <textarea name="catatan" rows="3" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2" placeholder="Catatan internal pengurus...">{{ old('catatan', $anggota->catatan) }}</textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition">
                    Simpan Perubahan Status
                </button>
            </form>
        </div>

        {{-- Kelola Akun Login --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Akun Login Anggota</h2>
                @if ($anggota->user)
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-xs space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Username:</span>
                            <span class="font-bold font-mono text-emerald-800">{{ $anggota->user->username }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Email:</span>
                            <span class="font-semibold text-slate-700">{{ $anggota->user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Wajib Ganti Password:</span>
                            <span class="font-bold {{ $anggota->user->must_change_password ? 'text-amber-600' : 'text-slate-600' }}">
                                {{ $anggota->user->must_change_password ? 'Ya' : 'Tidak' }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 text-xs text-amber-800 mb-4">
                        ⚠️ Anggota ini belum memiliki akun user login. Mengaktifkan status anggota di form sebelah akan membuatkan akun secara otomatis.
                    </div>
                @endif
            </div>

            @if ($anggota->user)
                <form method="POST" action="{{ route('admin.keanggotaan.reset-password', $anggota) }}" onsubmit="return confirm('Reset password akun ini ke password123?')">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition">
                        Reset Password ke 'password123'
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Riwayat Skor & Presensi Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Riwayat Skor Tembakan (Terbaru)</h2>
            <div class="space-y-2">
                @forelse ($anggota->skorTembakan as $s)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl text-xs">
                        <div>
                            <span class="font-bold text-slate-800">{{ $s->tanggal->translatedFormat('d M Y') }}</span> · Sesi {{ $s->sesi_ke }} · {{ $s->jarak_meter }}m
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-mono font-bold text-slate-800 text-sm">{{ $s->total_skor }} poin</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $s->status_validasi === 'valid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ ucfirst($s->status_validasi) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Belum ada catatan skor tembakan.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Riwayat Iuran & Pembayaran</h2>
            <div class="space-y-2">
                @forelse ($anggota->pembayaran as $p)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl text-xs">
                        <div>
                            <span class="font-bold text-slate-800">{{ $p->tanggal_bayar->translatedFormat('d M Y') }}</span>
                            <span class="text-slate-400">· {{ $p->periode ? 'Periode ' . $p->periode->translatedFormat('F Y') : 'Non-iuran' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-mono font-bold text-emerald-700">Rp{{ number_format($p->jumlah, 0, ',', '.') }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $p->status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Belum ada riwayat pembayaran.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
