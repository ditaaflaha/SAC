<x-guest-layout>
    <div class="mb-4 text-center">
        <div class="text-4xl mb-2">🔐</div>
        <h2 class="text-xl font-bold text-gray-900">Ganti Password Wajib</h2>
        <p class="text-sm text-gray-600 mt-1">
            Untuk keamanan akunmu di Sunnah Archery Community, silakan buat password baru sebelum melanjutkan.
        </p>
    </div>

    @if (session('info'))
        <div class="mb-4 p-3 bg-amber-50 border border-amber-200 text-amber-800 text-xs rounded-xl">
            {{ session('info') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf
        @method('PUT')

        <!-- Password Baru -->
        <div>
            <x-input-label for="password" value="Password Baru" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi Password Baru -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Ulangi Password Baru" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="underline text-sm text-gray-500 hover:text-red-600">
                    Batal / Keluar
                </button>
            </form>

            <x-primary-button class="ms-3 bg-emerald-600 hover:bg-emerald-700">
                Simpan Password Baru 🏹
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
