@extends('layouts.publik')

@section('title', 'Profil SAC')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    {{-- Header Profil --}}
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-[#E5E7EB] text-center relative overflow-hidden">
        <div class="pointer-events-none absolute -top-20 -right-20 w-64 h-64 rounded-full bg-primary-100 opacity-60 blur-3xl"></div>
        <div class="relative z-10">
            <img src="{{ asset('images/logo-sac.jpg') }}" alt="Sunnah Archery Community" class="h-20 w-auto object-contain mx-auto mb-4 rounded-xl">
            <span class="inline-block text-xs font-semibold text-primary-700 bg-primary-50 px-3 py-1 rounded-full mb-2">Profil Komunitas</span>
            <h1 class="text-3xl font-bold text-[#1F2937] mb-2">Sunnah Archery Community</h1>
            <p class="text-sm text-[#6B7280]">Komunitas panahan berbasis sunnah di Kota Batam, Kepulauan Riau</p>
        </div>
    </div>

    {{-- Tentang --}}
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-[#E5E7EB]">
        <h2 class="text-xl font-bold text-[#1F2937] mb-3">Tentang SAC</h2>
        <p class="text-sm text-[#6B7280] leading-relaxed">
            <strong class="text-[#1F2937]">Sunnah Archery Community (SAC)</strong> adalah komunitas panahan yang berdiri di Batam, Kepulauan Riau. SAC hadir untuk menghidupkan kembali sunnah Rasulullah ﷺ dalam olahraga panahan, sekaligus membina anggota agar mampu berprestasi di tingkat lokal, provinsi, bahkan nasional.
        </p>
        <p class="text-sm text-[#6B7280] leading-relaxed mt-3">
            Pelatih utama SAC adalah <strong class="text-[#1F2937]">Coach Revi Andika</strong>, yang berpengalaman dalam dunia panahan dan berkomitmen membimbing setiap anggota sesuai kemampuan masing-masing. Latihan SAC dikenal dengan suasana kekeluargaan yang hangat namun tetap serius dalam pencapaian teknik.
        </p>
    </div>

    {{-- Visi Misi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-7 shadow-sm border border-[#E5E7EB]">
            <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center text-xl mb-3">🎯</div>
            <h3 class="text-lg font-bold text-[#1F2937] mb-2">Visi</h3>
            <p class="text-[#6B7280] text-sm leading-relaxed">Menjadi komunitas panahan terdepan di Batam yang melahirkan pemanah berprestasi berbasis nilai-nilai Islami.</p>
        </div>
        <div class="bg-white rounded-2xl p-7 shadow-sm border border-[#E5E7EB]">
            <div class="w-10 h-10 rounded-xl bg-accent-100 text-primary-700 flex items-center justify-center text-xl mb-3">🚀</div>
            <h3 class="text-lg font-bold text-[#1F2937] mb-2">Misi</h3>
            <ul class="text-[#6B7280] text-sm leading-relaxed space-y-2">
                <li>• Menghidupkan sunnah panahan</li>
                <li>• Melatih secara konsisten dan terukur</li>
                <li>• Membangun jiwa sportivitas & ukhuwah</li>
                <li>• Mendorong anggota berprestasi di kejuaraan</li>
            </ul>
        </div>
    </div>

    {{-- Pelatih --}}
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-[#E5E7EB]">
        <h2 class="text-xl font-bold text-[#1F2937] mb-5">Pelatih</h2>
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-primary-100 text-primary-700 rounded-2xl flex items-center justify-center text-2xl flex-shrink-0">
                🏹
            </div>
            <div>
                <h3 class="text-lg font-bold text-[#1F2937]">Coach Revi Andika</h3>
                <p class="text-primary-600 font-semibold text-xs mb-1.5">Pelatih Utama SAC</p>
                <p class="text-[#6B7280] text-sm leading-relaxed">Berpengalaman dalam dunia panahan tradisional dan modern, berkomitmen untuk mengembangkan potensi setiap anggota SAC.</p>
            </div>
        </div>
    </div>

    {{-- Jadwal & Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-7 shadow-sm border border-[#E5E7EB]">
            <h3 class="text-base font-bold text-[#1F2937] mb-4">📅 Jadwal Latihan</h3>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <span class="px-2.5 py-0.5 rounded-lg bg-primary-50 text-primary-700 font-semibold text-xs mt-0.5">Wajib</span>
                    <div>
                        <div class="font-semibold text-[#1F2937] text-sm">Setiap Ahad (Minggu)</div>
                        <div class="text-[#6B7280] text-xs">Pukul 07.00 – 09.00 WIB</div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="px-2.5 py-0.5 rounded-lg bg-sky-50 text-sky-700 font-semibold text-xs mt-0.5">Mandiri</span>
                    <div>
                        <div class="font-semibold text-[#1F2937] text-sm">Latihan mandiri fleksibel</div>
                        <div class="text-[#6B7280] text-xs">Konfirmasi melalui grup WA</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-7 shadow-sm border border-[#E5E7EB]">
            <h3 class="text-base font-bold text-[#1F2937] mb-4">📍 Lokasi & Kontak</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-2 text-[#6B7280]">
                    <span class="flex-shrink-0">📍</span>
                    <span>Lapangan Archery SAC, Batam, Kepulauan Riau</span>
                </div>
                <div class="flex items-start gap-2 text-[#6B7280]">
                    <span class="flex-shrink-0">📸</span>
                    <a href="https://instagram.com" class="text-primary-600 hover:underline font-medium">@sunnah_archery_community</a>
                </div>
                <div class="flex items-start gap-2 text-[#6B7280]">
                    <span class="flex-shrink-0">💬</span>
                    <a href="https://wa.me/{{ env('SAC_ADMIN_WA') }}" class="text-primary-600 hover:underline font-medium">Hubungi Admin via WhatsApp</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
