@extends('layouts.anggota')

@section('title', $pengumuman->judul)

@section('content')
<div class="space-y-6 max-w-3xl">
    <div>
        <a href="{{ route('anggota.pengumuman') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900 transition">
            ← Kembali ke Daftar Pengumuman
        </a>
    </div>

    <article class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm space-y-6">
        <div class="border-b border-gray-100 pb-4 space-y-2">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <span class="font-bold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                    Warta Resmi
                </span>
                <span>•</span>
                <time datetime="{{ $pengumuman->tanggal->toDateString() }}">
                    {{ $pengumuman->tanggal->translatedFormat('l, d F Y') }}
                </time>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight leading-snug">
                {{ $pengumuman->judul }}
            </h1>
            <div class="text-xs text-gray-400 pt-1">
                Diumumkan oleh: <strong class="text-gray-700">{{ $pengumuman->penulis?->name ?? 'Admin SAC' }}</strong>
            </div>
        </div>

        <div class="prose prose-emerald text-sm text-gray-700 leading-relaxed whitespace-pre-line">
            {{ $pengumuman->isi }}
        </div>
    </article>
</div>
@endsection

