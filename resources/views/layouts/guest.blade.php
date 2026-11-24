<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Masuk — {{ config('app.name', 'Sunnah Archery Community') }}</title>
        <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-sac.jpg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-[#FAFAFB] relative overflow-x-hidden">
        {{-- Dekorasi lembut lingkaran blur pastel --}}
        <div class="pointer-events-none fixed inset-0 overflow-hidden z-0">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-[#EDE9FE] opacity-70 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-[#FCE7F3] opacity-70 blur-3xl"></div>
        </div>

        <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-[400px]">
                {{-- Logo & Judul --}}
                <div class="text-center mb-6">
                    <a href="{{ route('beranda') }}" class="inline-block">
                        <img src="{{ asset('images/logo-sac.jpg') }}"
                             alt="Sunnah Archery Community"
                             class="h-16 md:h-[72px] w-auto mx-auto object-contain rounded-xl" />
                    </a>
                    <h1 class="mt-3 font-semibold text-lg text-gray-800">
                        Sunnah Archery Community
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Masuk untuk memantau perkembangan latihanmu
                    </p>
                </div>

                {{-- Card Form --}}
                <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-[#E5E7EB] p-8">
                    {{ $slot }}
                </div>

                {{-- Link Kembali ke Beranda di bawah card --}}
                <div class="mt-5 text-center">
                    <a href="{{ route('beranda') }}"
                       class="inline-flex items-center justify-center text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
