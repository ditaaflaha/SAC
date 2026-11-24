@extends('layouts.admin')

@section('title', 'Pengumuman & Galeri Komunitas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Pengumuman & Galeri</h1>
            <p class="text-sm text-[#6B7280]">Publikasikan informasi penting ke website publik dan upload arsip dokumentasi kegiatan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Form Buat Pengumuman --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider mb-4">Buat Pengumuman Baru</h2>
            <form method="POST" action="{{ route('admin.pengumuman.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Judul Pengumuman</label>
                    <input type="text" name="judul" required placeholder="Judul info atau kegiatan..." class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ now()->toDateString() }}" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Isi Pengumuman Lengkap</label>
                    <textarea name="isi" rows="4" required placeholder="Tuliskan detail pengumuman di sini..." class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="publik" name="publik" value="1" checked class="rounded border-[#E5E7EB] text-primary-600 focus:ring-primary-500">
                    <label for="publik" class="text-xs font-medium text-[#1F2937]">Tampilkan di halaman Beranda Publik</label>
                </div>
                <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                    📢 Terbitkan Pengumuman
                </button>
            </form>
        </div>

        {{-- Form Upload Galeri --}}
        <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm">
            <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider mb-4">Unggah Foto Galeri</h2>
            <form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Judul / Keterangan Foto</label>
                    <input type="text" name="judul" required placeholder="Contoh: Latihan Rutin Ahad Pagi..." class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" value="{{ now()->toDateString() }}" required class="w-full text-sm border-[#E5E7EB] rounded-xl px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#1F2937] mb-1">File Foto (JPG/PNG, Maks. 4MB)</label>
                    <input type="file" name="foto" accept="image/*" required class="w-full text-xs text-[#6B7280] file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                </div>
                <button type="submit" class="w-full py-2.5 bg-primary-50 hover:bg-primary-100 text-primary-700 rounded-xl text-xs font-semibold transition">
                    📸 Unggah ke Galeri
                </button>
            </form>
        </div>
    </div>

    {{-- Daftar Pengumuman Terbit --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6 space-y-4">
        <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Daftar Pengumuman Terbit</h2>
        <div class="divide-y divide-[#E5E7EB]">
            @forelse ($pengumumanList as $p)
                <div class="py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm text-[#1F2937]">{{ $p->judul }}</h3>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $p->publik ? 'bg-primary-50 text-primary-700' : 'bg-gray-100 text-[#6B7280]' }}">
                                {{ $p->publik ? 'Publik' : 'Internal' }}
                            </span>
                        </div>
                        <p class="text-xs text-[#6B7280] mt-0.5">
                            📅 {{ $p->tanggal->translatedFormat('d M Y') }} · Ditulis oleh: {{ $p->penulis?->name }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('admin.pengumuman.destroy', $p) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-semibold text-rose-500 hover:text-rose-700">
                            Hapus
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-xs text-[#6B7280] py-4 text-center">Belum ada pengumuman.</p>
            @endforelse
        </div>
        <div class="pt-2">
            {{ $pengumumanList->links() }}
        </div>
    </div>

    {{-- Galeri Foto --}}
    <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm p-6 space-y-4">
        <h2 class="text-xs font-bold text-primary-600 uppercase tracking-wider">Galeri Foto Kegiatan</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse ($galeriList as $g)
                <div class="group relative rounded-xl overflow-hidden border border-[#E5E7EB] bg-[#FAFAFB]">
                    <img src="{{ asset('storage/' . $g->file) }}" alt="{{ $g->judul }}" class="w-full h-32 object-cover">
                    <div class="p-2">
                        <div class="font-bold text-[11px] text-[#1F2937] truncate">{{ $g->judul }}</div>
                        <div class="text-[10px] text-[#6B7280]">{{ $g->tanggal->format('d/m/Y') }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.galeri.destroy', $g) }}" onsubmit="return confirm('Hapus foto ini?')" class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1 bg-rose-600 text-white rounded-lg text-[10px]">
                            ✕
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-xs text-[#6B7280] col-span-full py-8 text-center">Belum ada foto di galeri.</p>
            @endforelse
        </div>
        <div class="pt-2">
            {{ $galeriList->links() }}
        </div>
    </div>
</div>
@endsection
