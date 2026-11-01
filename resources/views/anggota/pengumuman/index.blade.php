@extends('layouts.anggota')

@section('title', 'Pengumuman Komunitas')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Pengumuman & Informasi Klub</h1>
        <p class="text-sm text-gray-500">Pemberitahuan resmi jadwal event, kejuaraan, dan agenda Sunnah Archery Community.</p>
    </div>

    <div class="space-y-4">
        @forelse ($pengumumanList as $p)
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm hover:border-emerald-200 transition space-y-3">
                <div class="flex items-center justify-between text-xs text-gray-400">
                    <span class="inline-flex items-center gap-1 font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                        📢 Warta SAC
                    </span>
                    <span>{{ $p->tanggal->translatedFormat('l, d F Y') }}</span>
                </div>

                <h2 class="text-lg font-extrabold text-gray-900">
                    <a href="{{ route('anggota.pengumuman.show', $p) }}" class="hover:text-emerald-600 transition">
                        {{ $p->judul }}
                    </a>
                </h2>

                <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">
                    {{ $p->isi }}
                </p>

                <div class="pt-2 flex items-center justify-between">
                    <span class="text-xs text-gray-400">
                        Ditulis oleh: <strong>{{ $p->penulis?->name ?? 'Admin SAC' }}</strong>
                    </span>
                    <a href="{{ route('anggota.pengumuman.show', $p) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                        Baca Selengkapnya →
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center text-gray-400 text-sm">
                Belum ada pengumuman yang diterbitkan saat ini.
            </div>
        @endforelse
    </div>

    <div>
        {{ $pengumumanList->links() }}
    </div>
</div>
@endsection

