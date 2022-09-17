<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <h1 class="text-lg font-semibold">{{ __('Register')  }}</h1>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" 
                :placeholder="__('Name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-8">

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" 
                :placeholder="__('Email')" required />
            </div>

            <!-- Password -->
            <div class="mt-8">

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" 
                                :placeholder="__('Password')" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-8">

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required 
                                :placeholder="__('Confirm Password')" />
            </div>

            <div class="mt-8">
                <x-button class="inline-block bg-blue-600 w-full justify-center mb-4">
                    {{ __('Register') }}
                </x-button>

                <p>アカウントをお持ちの方はこちらから</p>

                <a class=" text-sm text-blue-500 font-semibold" href="{{ route('login') }}">
                    {{ __('Login')  }}
                </a>

            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
