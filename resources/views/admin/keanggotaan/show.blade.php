@extends('layouts.admin')

@section('title', 'Detail Anggota — ' . $anggota->nama)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.keanggotaan') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition">
            ← Kembali ke Daftar Anggota
        </a>
        <form method="POST" action="{{ route('admin.keanggotaan.destroy', $anggota) }}" onsubmit="return confirm('Yakin ingin menghapus anggota {{ addslashes($anggota->nama) }} beserta akun login dan seluruh riwayatnya?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5">
                <span>🗑️</span> Hapus Anggota
            </button>
        </form>
    </div>

    {{-- Kartu Profil & Status --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Biodata Singkat --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm space-y-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-primary-100 text-primary-700 font-extrabold text-2xl flex items-center justify-center">
                    {{ strtoupper(substr($anggota->nama, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-xl font-bold text-[#1F2937]">{{ $anggota->nama }}</h1>
                    <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-semibold rounded-full bg-primary-50 text-primary-700 capitalize">
                        Anggota {{ $anggota->jenis_keanggotaan }}
                    </span>
                </div>
            </div>

            <div class="border-t border-[#E5E7EB] pt-4 space-y-2 text-xs">
                <div class="flex justify-between py-1">
                    <span class="text-[#6B7280]">WhatsApp</span>
                    <span class="font-semibold text-[#1F2937]">{{ $anggota->no_wa ?: '-' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#6B7280]">Gender</span>
                    <span class="font-semibold text-[#1F2937]">{{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki (Ikhwan)' : 'Perempuan (Akhwat)' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#6B7280]">Usia</span>
                    <span class="font-semibold text-[#1F2937]">{{ $anggota->umur() ? $anggota->umur() . ' tahun' : '-' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#6B7280]">Busur Pribadi</span>
                    <span class="font-semibold text-[#1F2937]">{{ $anggota->jenis_busur ?: 'Belum punya' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-[#6B7280]">Tanggal Gabung</span>
                    <span class="font-semibold text-[#1F2937]">{{ $anggota->tanggal_gabung ? $anggota->tanggal_gabung->translatedFormat('d M Y') : '-' }}</span>
                </div>
            </div>

            @if ($anggota->catatan)
                <div class="border-t border-[#E5E7EB] pt-3">
                    <span class="text-xs text-[#6B7280] block mb-1">Catatan Admin:</span>
                    <p class="text-xs text-[#1F2937] bg-[#FAFAFB] border border-[#E5E7EB] p-2.5 rounded-xl">{{ $anggota->catatan }}</p>
                </div>
            @endif
        </div>

        {{-- Form Ubah Status & Jenis Keanggotaan --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider mb-4">Ubah Status & Jenis</h2>
            <form method="POST" action="{{ route('admin.keanggotaan.status', $anggota) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Status Keanggotaan</label>
                    <select name="status_keanggotaan" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="menunggu" {{ $anggota->status_keanggotaan === 'menunggu' ? 'selected' : '' }}>Menunggu Approval</option>
                        <option value="aktif" {{ $anggota->status_keanggotaan === 'aktif' ? 'selected' : '' }}>Aktif (Disetujui)</option>
                        <option value="nonaktif" {{ $anggota->status_keanggotaan === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="ditolak" {{ $anggota->status_keanggotaan === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Jenis Keanggotaan</label>
                    <select name="jenis_keanggotaan" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="reguler" {{ $anggota->jenis_keanggotaan === 'reguler' ? 'selected' : '' }}>Reguler (Member Penuh)</option>
                        <option value="trial" {{ $anggota->jenis_keanggotaan === 'trial' ? 'selected' : '' }}>Trial (Uji Coba)</option>
                        <option value="private" {{ $anggota->jenis_keanggotaan === 'private' ? 'selected' : '' }}>Private Class</option>
                        <option value="pasif" {{ $anggota->jenis_keanggotaan === 'pasif' ? 'selected' : '' }}>Pasif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Catatan Tambahan</label>
                    <textarea name="catatan" rows="3" class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Catatan internal pengurus...">{{ old('catatan', $anggota->catatan) }}</textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                    Simpan Perubahan Status
                </button>
            </form>
        </div>

        {{-- Kelola Akun Login --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider mb-4">Akun Login Anggota</h2>
                @if ($anggota->user)
                    <div class="p-4 bg-primary-50/70 rounded-2xl border border-primary-100 text-xs space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span class="text-[#6B7280]">Username:</span>
                            <span class="font-bold font-mono text-primary-700">{{ $anggota->user->username }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B7280]">Email:</span>
                            <span class="font-semibold text-[#1F2937]">{{ $anggota->user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B7280]">Wajib Ganti Password:</span>
                            <span class="font-bold {{ $anggota->user->must_change_password ? 'text-amber-600' : 'text-[#6B7280]' }}">
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
                    <button type="submit" class="w-full py-2.5 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-xl text-xs font-semibold transition">
                        Reset Password ke 'password123'
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Riwayat Skor & Presensi Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider mb-4">Riwayat Skor Tembakan (Terbaru)</h2>
            <div class="space-y-2">
                @forelse ($anggota->skorTembakan as $s)
                    <div class="flex items-center justify-between p-3 bg-[#FAFAFB] border border-[#E5E7EB] rounded-xl text-xs">
                        <div>
                            <span class="font-bold text-[#1F2937]">{{ $s->tanggal->translatedFormat('d M Y') }}</span> · Sesi {{ $s->sesi_ke }} · {{ $s->jarak_meter }}m
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-mono font-bold text-[#1F2937] text-sm">{{ $s->total_skor }} poin</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $s->status_validasi === 'valid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ ucfirst($s->status_validasi) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#6B7280] py-4 text-center">Belum ada catatan skor tembakan.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider mb-4">Riwayat Presensi Latihan</h2>
            <div class="space-y-2">
                @forelse ($anggota->presensi as $pr)
                    <div class="flex items-center justify-between p-3 bg-[#FAFAFB] border border-[#E5E7EB] rounded-xl text-xs">
                        <div>
                            <span class="font-bold text-[#1F2937]">{{ $pr->jadwalLatihan?->tanggal?->translatedFormat('d M Y') ?? '-' }}</span>
                            <span class="text-[#6B7280]">· {{ $pr->jadwalLatihan?->lokasi ?? 'Lapangan SAC' }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700">
                            Hadir ({{ $pr->waktu_hadir?->format('H:i') ?? '-' }})
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-[#6B7280] py-4 text-center">Belum ada riwayat presensi.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
