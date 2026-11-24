@extends('layouts.anggota')

@section('title', $pengumuman->judul)

@section('content')
<div class="space-y-6 max-w-3xl">
    <div>
        <a href="{{ route('anggota.pengumuman') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition">
            ← Kembali ke Daftar Pengumuman
        </a>
    </div>

    <article class="bg-white rounded-2xl border border-[#E5E7EB] p-8 shadow-sm space-y-6">
        <div class="border-b border-[#E5E7EB] pb-4 space-y-2">
            <div class="flex items-center gap-2 text-xs text-[#6B7280]">
                <span class="font-semibold text-primary-700 bg-primary-50 px-2.5 py-0.5 rounded-full">
                    Warta Resmi
                </span>
                <span>•</span>
                <time datetime="{{ $pengumuman->tanggal->toDateString() }}">
                    {{ $pengumuman->tanggal->translatedFormat('l, d F Y') }}
                </time>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-[#1F2937] tracking-tight leading-snug">
                {{ $pengumuman->judul }}
            </h1>
            <div class="text-xs text-[#6B7280] pt-1">
                Diumumkan oleh: <strong class="text-[#1F2937]">{{ $pengumuman->penulis?->name ?? 'Admin SAC' }}</strong>
            </div>
        </div>

        <div class="text-sm text-[#1F2937] leading-relaxed whitespace-pre-line">
            {{ $pengumuman->isi }}
        </div>
    </article>
</div>
@endsection
