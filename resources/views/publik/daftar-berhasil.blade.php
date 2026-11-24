@extends('layouts.publik')

@section('title', 'Pendaftaran Berhasil')

@section('content')

<section class="max-w-lg mx-auto px-4 sm:px-6 py-20 text-center">
    <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] p-8 sm:p-10">
        <div class="w-16 h-16 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-5 text-3xl">✅</div>
        <h1 class="text-2xl font-bold text-[#1F2937] mb-2">Pendaftaran Diterima!</h1>
        <p class="text-[#6B7280] text-sm mb-2">
            Hai, <strong class="text-[#1F2937]">{{ $nama }}</strong>! Data pendaftaranmu sudah tersimpan.
        </p>
        <p class="text-[#6B7280] text-sm mb-8 leading-relaxed">
            Langkah terakhir: konfirmasi ke Admin SAC melalui WhatsApp agar jadwal sesi latihan pertamamu segera disiapkan.
        </p>

        <a href="{{ $waLink }}"
           target="_blank"
           class="inline-flex items-center justify-center gap-2.5 w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3.5 rounded-xl transition-colors text-sm shadow-sm">
            <span>💬</span>
            <span>Konfirmasi via WhatsApp Admin</span>
        </a>

        <div class="mt-6">
            <a href="{{ route('beranda') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
</section>

@endsection
