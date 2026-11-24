@extends('layouts.publik')

@section('title', 'Daftar Anggota')

@section('content')

<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="text-center mb-8 reveal-item">
        <span class="inline-block text-xs font-bold uppercase tracking-wider text-primary-600 bg-primary-50 px-3 py-1 rounded-full mb-2">Form Pendaftaran</span>
        <h1 class="text-2xl sm:text-3xl font-bold text-[#1F2937]">Gabung Latihan Bareng SAC</h1>
        <p class="text-sm text-[#6B7280] mt-1">Isi formulir singkat di bawah ini, lalu konfirmasi jadwalmu via WhatsApp Admin.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] p-6 sm:p-8 reveal-item">
        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl px-4 py-3 mb-6 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('daftar.store') }}" class="space-y-5">
            @csrf

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-semibold text-[#1F2937] mb-1.5" for="nama">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                       placeholder="Masukkan nama lengkap" required
                       class="w-full border border-[#E5E7EB] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
            </div>

            {{-- No WA --}}
            <div>
                <label class="block text-sm font-semibold text-[#1F2937] mb-1.5" for="no_wa">
                    Nomor WhatsApp <span class="text-rose-500">*</span>
                </label>
                <input type="tel" id="no_wa" name="no_wa" value="{{ old('no_wa') }}"
                       placeholder="08xx-xxxx-xxxx" required
                       class="w-full border border-[#E5E7EB] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
            </div>

            {{-- Jenis Kelamin & Tanggal Lahir --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-[#1F2937] mb-1.5">
                        Jenis Kelamin <span class="text-rose-500">*</span>
                    </label>
                    <select name="jenis_kelamin" required
                            class="w-full border border-[#E5E7EB] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Pilih...</option>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki (Ikhwan)</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan (Akhwat)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#1F2937] mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                           class="w-full border border-[#E5E7EB] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>

            {{-- Punya Busur --}}
            <div>
                <label class="block text-sm font-semibold text-[#1F2937] mb-2">
                    Punya busur sendiri? <span class="text-rose-500">*</span>
                </label>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="punya_busur" value="ya" {{ old('punya_busur') === 'ya' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500">
                        <span class="text-sm text-[#1F2937]">Ya, punya</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="punya_busur" value="tidak" {{ old('punya_busur', 'tidak') === 'tidak' ? 'checked' : '' }}
                               class="text-primary-600 focus:ring-primary-500">
                        <span class="text-sm text-[#1F2937]">Belum punya</span>
                    </label>
                </div>
            </div>

            {{-- Jenis Pilihan --}}
            <div>
                <label class="block text-sm font-semibold text-[#1F2937] mb-2">
                    Pilih Program / Keanggotaan <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach ([
                        ['trial', '🎯', 'Trial', 'Rp75.000/pertemuan'],
                        ['private', '🏹', 'Private Class', 'Rp500.000/6x'],
                        ['reguler', '🌟', 'Join Komunitas', 'Rp350.000'],
                    ] as [$val, $icon, $label, $harga])
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_pilihan" value="{{ $val }}"
                                   class="sr-only peer"
                                   {{ old('jenis_pilihan', request('pilihan', 'trial')) === $val ? 'checked' : '' }}>
                            <div class="border-2 border-[#E5E7EB] peer-checked:border-primary-600 peer-checked:bg-primary-50 rounded-2xl p-3.5 text-center transition-all hover:border-primary-300">
                                <div class="text-xl mb-1">{{ $icon }}</div>
                                <div class="text-xs font-bold text-[#1F2937]">{{ $label }}</div>
                                <div class="text-xs text-[#6B7280] mt-0.5">{{ $harga }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Follow IG --}}
            <div class="bg-primary-50/70 border border-primary-200 rounded-2xl p-4">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="follow_ig" value="1"
                           {{ old('follow_ig') ? 'checked' : '' }}
                           class="mt-0.5 text-primary-600 focus:ring-primary-500 rounded border-gray-300">
                    <span class="text-xs sm:text-sm text-[#1F2937]">
                        Saya sudah follow Instagram <strong class="text-primary-700">@sunnah_archery_community</strong>
                    </span>
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl transition-colors text-sm shadow-sm">
                Kirim Pendaftaran
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-[#6B7280] mt-6">
        Sudah punya akun anggota? <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 hover:underline font-semibold">Masuk di sini</a>
    </p>
</section>

@endsection
