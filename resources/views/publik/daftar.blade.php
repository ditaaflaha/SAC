@extends('layouts.publik')

@section('title', 'Daftar Anggota')

@section('content')

<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="text-center mb-8">
        <div class="text-4xl mb-3">🏹</div>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Daftar ke SAC</h1>
        <p class="text-gray-500">Isi form di bawah, dan kami akan segera menghubungimu!</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl px-4 py-3 mb-6 text-sm">
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
                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="nama">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                       placeholder="Nama kamu" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-400 transition-colors @error('nama') border-red-400 bg-red-50 @enderror">
            </div>

            {{-- No WA --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5" for="no_wa">
                    Nomor WhatsApp <span class="text-red-500">*</span>
                </label>
                <input type="tel" id="no_wa" name="no_wa" value="{{ old('no_wa') }}"
                       placeholder="08xx-xxxx-xxxx" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-400 transition-colors @error('no_wa') border-red-400 bg-red-50 @enderror">
            </div>

            {{-- Jenis Kelamin & Tanggal Lahir --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_kelamin" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('jenis_kelamin') border-red-400 @enderror">
                        <option value="">Pilih...</option>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>

            {{-- Punya Busur --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Punya busur sendiri? <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="punya_busur" value="ya" {{ old('punya_busur') === 'ya' ? 'checked' : '' }}
                               class="text-emerald-500 focus:ring-emerald-400">
                        <span class="text-sm text-gray-700">Ya, punya</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="punya_busur" value="tidak" {{ old('punya_busur') === 'tidak' ? 'checked' : '' }}
                               class="text-emerald-500 focus:ring-emerald-400">
                        <span class="text-sm text-gray-700">Belum punya</span>
                    </label>
                </div>
            </div>

            {{-- Jenis Pilihan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pilih Jenis Keanggotaan <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach ([
                        ['trial', '🎯', 'Trial', 'Rp75.000/pertemuan'],
                        ['private', '🏹', 'Private Class', 'Rp500.000/6x'],
                        ['reguler', '🌟', 'Join Komunitas', 'Rp350.000 + iuran'],
                    ] as [$val, $icon, $label, $harga])
                        <label class="cursor-pointer">
                            <input type="radio" name="jenis_pilihan" value="{{ $val }}"
                                   class="sr-only peer"
                                   {{ old('jenis_pilihan', request('pilihan')) === $val ? 'checked' : '' }}>
                            <div class="border-2 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 rounded-2xl p-3 text-center transition-all hover:border-emerald-300">
                                <div class="text-xl mb-1">{{ $icon }}</div>
                                <div class="text-xs font-bold text-gray-800">{{ $label }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $harga }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Follow IG --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" name="follow_ig" value="1"
                           {{ old('follow_ig') ? 'checked' : '' }}
                           class="mt-0.5 text-emerald-500 focus:ring-emerald-400 rounded">
                    <span class="text-sm text-gray-700">
                        Saya sudah follow Instagram <strong>@sunnah_archery_community</strong>
                        (wajib ya! ini syaratnya 😄)
                    </span>
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 rounded-2xl transition-colors text-sm shadow-sm hover:shadow-md">
                🚀 Kirim Pendaftaran
            </button>
        </form>
    </div>

    <p class="text-center text-xs text-gray-400 mt-6">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-emerald-600 hover:underline font-medium">Masuk di sini</a>
    </p>
</section>

@endsection
