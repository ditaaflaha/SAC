@extends('layouts.publik')

@section('title', 'Dokumentasi')

@section('content')

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">📸 Dokumentasi Kegiatan</h1>
        <p class="text-gray-500">Momen-momen keseruan latihan dan kejuaraan SAC.</p>
    </div>

    @if ($galeri->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <div class="text-5xl mb-3">📷</div>
            <p class="font-medium">Belum ada foto yang diunggah.</p>
            <p class="text-sm mt-1">Nantikan dokumentasi kegiatan SAC di sini!</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach ($galeri as $foto)
                <div class="group aspect-square bg-emerald-50 rounded-2xl overflow-hidden border border-gray-100 hover:shadow-md transition-all duration-200 flex items-center justify-center cursor-pointer">
                    {{-- Jika file ada: <img src="{{ Storage::url($foto->file) }}" alt="{{ $foto->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200"> --}}
                    <div class="text-center p-3">
                        <div class="text-4xl mb-2">🏹</div>
                        <p class="text-xs text-gray-500 font-medium truncate">{{ $foto->judul }}</p>
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
