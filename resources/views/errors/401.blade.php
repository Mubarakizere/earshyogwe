<x-guest-layout>
    <div class="text-center py-10">
        <h1 class="text-9xl font-black text-brand-600 drop-shadow-md">401</h1>
        <h2 class="mt-6 text-2xl font-bold text-gray-900 tracking-tight sm:text-3xl">Unauthorized</h2>
        <p class="mt-4 text-base text-gray-600">Please login to continue accessing this resource.</p>
        
        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Go to Login
            </a>
            <button onclick="window.history.back()" class="inline-flex items-center justify-center px-5 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors duration-200 shadow-sm">
                Go back
            </button>
        </div>
    </div>
</x-guest-layout>
