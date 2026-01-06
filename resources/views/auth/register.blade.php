<x-guest-layout>
    <div class="text-center lg:text-left mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Create Account</h2>
        <p class="mt-2 text-sm text-gray-600">Join the EARS HYOGWE management system today.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div class="relative">
            <x-input-label for="name" :value="__('Full Name')" class="sr-only" />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
            </div>
            <x-text-input id="name" class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition duration-150 ease-in-out shadow-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Full name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="relative">
            <x-input-label for="email" :value="__('Email Address')" class="sr-only" />
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                </svg>
            </div>
            <x-text-input id="email" class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition duration-150 ease-in-out shadow-sm" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Email address" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="relative">
            <x-input-label for="password" :value="__('Password')" class="sr-only" />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
            </div>
            <x-text-input id="password" class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition duration-150 ease-in-out shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="Password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="relative">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="sr-only" />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
            </div>
            <x-text-input id="password_confirmation" class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition duration-150 ease-in-out shadow-sm"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="Confirm password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-brand-700 hover:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition duration-150 ease-in-out shadow-lg transform hover:-translate-y-0.5">
                {{ __('Create Account') }}
            </button>
        </div>

        <div class="text-center pt-4">
            <p class="text-sm text-gray-600">
                Already registered? 
                <a href="{{ route('login') }}" class="font-bold text-brand-700 hover:text-brand-900 transition duration-150">Sign In</a>
            </p>
        </div>
    </form>
</x-guest-layout>
