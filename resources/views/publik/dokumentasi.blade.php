@extends('layouts.publik')

@section('title', 'Dokumentasi')

@section('content')

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <span class="text-xs font-semibold text-primary-700 bg-primary-50 px-3 py-1 rounded-full">Galeri SAC</span>
        <h1 class="text-2xl md:text-3xl font-bold text-[#1F2937] mt-2 mb-1">📸 Dokumentasi Kegiatan</h1>
        <p class="text-sm text-[#6B7280]">Momen-momen keseruan latihan dan kejuaraan Sunnah Archery Community.</p>
    </div>

    @if ($galeri->isEmpty())
        <div class="bg-white rounded-2xl border border-[#E5E7EB] shadow-sm text-center py-16 text-[#6B7280]">
            <div class="text-4xl mb-3">📷</div>
            <p class="font-semibold text-[#1F2937]">Belum ada foto yang diunggah.</p>
            <p class="text-xs mt-1">Nantikan dokumentasi kegiatan SAC di sini!</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach ($galeri as $foto)
                <div class="group aspect-square bg-white rounded-2xl overflow-hidden border border-[#E5E7EB] shadow-sm hover:border-primary-300 transition-all duration-200 flex items-center justify-center cursor-pointer">
                    <div class="text-center p-4">
                        <div class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center mx-auto text-3xl mb-3">🏹</div>
                        <p class="text-xs text-[#1F2937] font-semibold truncate">{{ $foto->judul }}</p>
                        <p class="text-[11px] text-[#6B7280] mt-0.5">{{ $foto->tanggal->translatedFormat('d M Y') }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $galeri->links() }}
        </div>
    @endif
</section>

@endsection
