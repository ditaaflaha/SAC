@extends('layouts.admin')

@section('title', 'Pengumuman & Galeri Komunitas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Pengumuman & Galeri</h1>
            <p class="text-sm text-slate-500">Publikasikan informasi penting ke website publik dan upload arsip dokumentasi kegiatan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Form Buat Pengumuman --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Buat Pengumuman Baru</h2>
            <form method="POST" action="{{ route('admin.pengumuman.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Judul Pengumuman</label>
                    <input type="text" name="judul" required placeholder="Judul info atau kegiatan..." class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ now()->toDateString() }}" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Isi Pengumuman Lengkap</label>
                    <textarea name="isi" rows="4" required placeholder="Tuliskan detail pengumuman di sini..." class="w-full text-sm border-slate-200 rounded-xl px-3 py-2"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="publik" name="publik" value="1" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="publik" class="text-xs font-medium text-slate-700">Tampilkan di halaman Beranda Publik</label>
                </div>
                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                    📢 Terbitkan Pengumuman
                </button>
            </form>
        </div>

        {{-- Form Upload Galeri --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Unggah Foto Galeri</h2>
            <form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Judul / Keterangan Foto</label>
                    <input type="text" name="judul" required placeholder="Contoh: Latihan Rutin Ahad Pagi..." class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Kegiatan</label>
                    <input type="date" name="tanggal" value="{{ now()->toDateString() }}" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">File Foto (JPG/PNG, Maks. 4MB)</label>
                    <input type="file" name="foto" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                </div>
                <button type="submit" class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition shadow-sm">
                    📸 Unggah ke Galeri
                </button>
            </form>
        </div>
    </div>

    {{-- Daftar Pengumuman Terbit --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Daftar Pengumuman Terbit</h2>
        <div class="divide-y divide-slate-100">
            @forelse ($pengumumanList as $p)
                <div class="py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-sm text-slate-800">{{ $p->judul }}</h3>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $p->publik ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $p->publik ? 'Publik' : 'Internal' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
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
                <p class="text-xs text-slate-400 py-4 text-center">Belum ada pengumuman.</p>
            @endforelse
        </div>
        <div class="pt-2">
            {{ $pengumumanList->links() }}
        </div>
    </div>

    {{-- Galeri Foto --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4">
        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Galeri Foto Kegiatan</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse ($galeriList as $g)
                <div class="group relative rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                    <img src="{{ asset('storage/' . $g->file) }}" alt="{{ $g->judul }}" class="w-full h-32 object-cover">
                    <div class="p-2">
                        <div class="font-bold text-[11px] text-slate-800 truncate">{{ $g->judul }}</div>
                        <div class="text-[10px] text-slate-400">{{ $g->tanggal->format('d/m/Y') }}</div>
                    </div>
                    <form method="POST" action="{{ route('admin.galeri.destroy', $g) }}" onsubmit="return confirm('Hapus foto ini?')" class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1 bg-red-600 text-white rounded-lg text-[10px]">
                            ✕
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-xs text-slate-400 col-span-full py-8 text-center">Belum ada foto di galeri.</p>
            @endforelse
        </div>
        <div class="pt-2">
            {{ $galeriList->links() }}
        </div>
    </div>
</div>
@endsection
