@extends('layouts.publik')

@section('title', 'Profil SAC')

@section('content')

{{-- HERO --}}
<section class="bg-gradient-to-br from-teal-700 to-emerald-600 text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <img src="{{ asset('images/logo-sac.jpg') }}" alt="Sunnah Archery Community" class="w-24 h-24 object-contain bg-white rounded-3xl p-2 mx-auto mb-4 shadow-md">
        <h1 class="text-4xl font-extrabold mb-3">Sunnah Archery Community</h1>
        <p class="text-lg text-white/85">Komunitas panahan berbasis sunnah di Kota Batam, Kepulauan Riau</p>
    </div>
</section>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-12">

    {{-- Tentang --}}
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-4">Tentang SAC</h2>
        <p class="text-gray-600 leading-relaxed">
            <strong>Sunnah Archery Community (SAC)</strong> adalah komunitas panahan yang berdiri di Batam, Kepulauan Riau. SAC hadir untuk menghidupkan kembali sunnah Rasulullah ﷺ dalam olahraga panahan, sekaligus membina anggota agar mampu berprestasi di tingkat lokal, provinsi, bahkan nasional.
        </p>
        <p class="text-gray-600 leading-relaxed mt-3">
            Pelatih utama SAC adalah <strong>Coach Revi Andika</strong>, yang berpengalaman dalam dunia panahan dan berkomitmen membimbing setiap anggota sesuai kemampuan masing-masing. Latihan SAC dikenal dengan suasana kekeluargaan yang hangat namun tetap serius dalam pencapaian teknik.
        </p>
    </div>

    {{-- Visi Misi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-emerald-50 rounded-3xl p-7 border border-emerald-100">
            <div class="text-3xl mb-3">🎯</div>
            <h3 class="text-lg font-extrabold text-gray-900 mb-2">Visi</h3>
            <p class="text-gray-600 text-sm leading-relaxed">Menjadi komunitas panahan terdepan di Batam yang melahirkan pemanah berprestasi berbasis nilai-nilai Islami.</p>
        </div>
        <div class="bg-teal-50 rounded-3xl p-7 border border-teal-100">
            <div class="text-3xl mb-3">🚀</div>
            <h3 class="text-lg font-extrabold text-gray-900 mb-2">Misi</h3>
            <ul class="text-gray-600 text-sm leading-relaxed space-y-2">
                <li>✅ Menghidupkan sunnah panahan</li>
                <li>✅ Melatih secara konsisten dan terukur</li>
                <li>✅ Membangun jiwa sportivitas & ukhuwah</li>
                <li>✅ Mendorong anggota berprestasi di kejuaraan</li>
            </ul>
        </div>
    </div>

    {{-- Pelatih --}}
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
        <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Pelatih</h2>
        <div class="flex items-center gap-5">
            <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0">
                🏹
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Coach Revi Andika</h3>
                <p class="text-emerald-600 font-semibold text-sm mb-2">Pelatih Utama SAC</p>
                <p class="text-gray-500 text-sm leading-relaxed">Berpengalaman dalam dunia panahan tradisional dan modern, berkomitmen untuk mengembangkan potensi setiap anggota SAC.</p>
            </div>
        </div>
    </div>

    {{-- Jadwal & Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-3xl p-7 shadow-sm border border-gray-100">
            <h3 class="text-lg font-extrabold text-gray-900 mb-4">📅 Jadwal Latihan</h3>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <span class="text-emerald-500 font-bold text-sm mt-0.5">Wajib</span>
                    <div>
                        <div class="font-semibold text-gray-800 text-sm">Setiap Ahad (Minggu)</div>
                        <div class="text-gray-500 text-sm">Pukul 07.00 – 09.00 WIB</div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-blue-500 font-bold text-sm mt-0.5">Mandiri</span>
                    <div>
                        <div class="font-semibold text-gray-800 text-sm">Latihan mandiri fleksibel</div>
                        <div class="text-gray-500 text-sm">Konfirmasi melalui grup WA</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-7 shadow-sm border border-gray-100">
            <h3 class="text-lg font-extrabold text-gray-900 mb-4">📍 Lokasi & Kontak</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-2 text-gray-600">
                    <span class="flex-shrink-0">📍</span>
                    <span>Lapangan Archery SAC, Batam, Kepulauan Riau</span>
                </div>
                <div class="flex items-start gap-2 text-gray-600">
                    <span class="flex-shrink-0">📸</span>
                    <a href="https://instagram.com" class="text-emerald-600 hover:underline">@sunnah_archery_community</a>
                </div>
                <div class="flex items-start gap-2 text-gray-600">
                    <span class="flex-shrink-0">💬</span>
                    <a href="https://wa.me/{{ env('SAC_ADMIN_WA') }}" class="text-emerald-600 hover:underline">Hubungi Admin via WhatsApp</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
