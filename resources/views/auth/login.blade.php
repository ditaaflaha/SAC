<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email atau Username -->
        <div>
            <x-input-label for="login" value="Email atau Username" class="text-gray-700 font-medium" />
            <x-text-input id="login"
                          class="block mt-1.5 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-xl"
                          type="text"
                          name="login"
                          :value="old('login')"
                          placeholder="Masukkan email atau username"
                          required
                          autofocus
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('login')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Password" class="text-gray-700 font-medium" />
            <x-text-input id="password"
                          class="block mt-1.5 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-xl"
                          type="password"
                          name="password"
                          placeholder="••••••••"
                          required
                          autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Ingat saya -->
        <div class="pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me"
                       type="checkbox"
                       class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full py-2.5 px-4 bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white font-semibold text-sm rounded-xl shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                Masuk
            </button>
        </div>
    </form>
</x-guest-layout>
