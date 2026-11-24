@extends('layouts.anggota')

@section('title', 'Pengumuman Komunitas')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-[#1F2937] tracking-tight">Pengumuman & Informasi Klub</h1>
        <p class="text-sm text-[#6B7280]">Pemberitahuan resmi jadwal event, kejuaraan, dan agenda Sunnah Archery Community.</p>
    </div>

    <div class="space-y-4">
        @forelse ($pengumumanList as $p)
            <div class="bg-white rounded-2xl border border-[#E5E7EB] p-6 shadow-sm hover:border-primary-200 transition space-y-3">
                <div class="flex items-center justify-between text-xs text-[#6B7280]">
                    <span class="inline-flex items-center gap-1 font-semibold text-primary-700 bg-primary-50 px-2.5 py-1 rounded-full">
                        📢 Warta SAC
                    </span>
                    <span>{{ $p->tanggal->translatedFormat('l, d F Y') }}</span>
                </div>

                <h2 class="text-lg font-bold text-[#1F2937]">
                    <a href="{{ route('anggota.pengumuman.show', $p) }}" class="hover:text-primary-600 transition">
                        {{ $p->judul }}
                    </a>
                </h2>

                <p class="text-sm text-[#6B7280] leading-relaxed line-clamp-3">
                    {{ $p->isi }}
                </p>

                <div class="pt-2 flex items-center justify-between">
                    <span class="text-xs text-[#6B7280]">
                        Ditulis oleh: <strong class="text-[#1F2937]">{{ $p->penulis?->name ?? 'Admin SAC' }}</strong>
                    </span>
                    <a href="{{ route('anggota.pengumuman.show', $p) }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700">
                        Baca Selengkapnya →
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-[#E5E7EB] p-12 text-center text-[#6B7280] text-sm">
                Belum ada pengumuman yang diterbitkan saat ini.
            </div>
        @endforelse
    </div>

    <div>
        {{ $pengumumanList->links() }}
    </div>
</div>
@endsection
