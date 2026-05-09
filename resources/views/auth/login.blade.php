<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input 
                id="email" 
                class="block mt-1 w-full" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input 
                id="password" 
                class="block mt-1 w-full"
                type="password"
                name="password"
                required 
                autocomplete="current-password" 
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember -->
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                    name="remember"
                >
                <span class="text-gray-600">Remember me</span>
            </label>

            <!-- @if (Route::has('password.request'))
                <a 
                    href="{{ route('password.request') }}"
                    class="text-indigo-600 hover:underline"
                >
                    Forgot password?
                </a>
            @endif -->
        </div>

        <!-- Button -->
        <div>
            <x-primary-button class="w-full justify-center">
                Log in
            </x-primary-button>
        </div>

        <!-- Register -->
        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-600">
                Don't have an account?
                <a 
                    href="{{ route('register') }}" 
                    class="text-indigo-600 hover:underline font-medium"
                >
                    Register
                </a>
            </p>
        @endif
    </form>
</x-guest-layout>