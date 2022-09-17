<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <h1 class="text-lg font-semibold">{{ __('Login')  }}</h1>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" 
                :placeholder="__('Email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-8">

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" 
                                :placeholder="__('Password')"/>
            </div>

            <!-- Remember Me -->
            <div class="mt-8">
                <x-button class="inline-block bg-blue-600 w-full justify-center mb-4">
                    {{ __('Login') }}
                </x-button>

                <p>アカウントをお持ちでない方はこちらから</p>

                <a class=" text-sm text-blue-500 font-semibold" href="{{ route('register') }}">
                    {{ __('Register')  }}
                </a>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
