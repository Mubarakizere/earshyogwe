<x-guest-layout>
    <div class="text-center py-10">
        <h1 class="text-9xl font-black text-brand-600 drop-shadow-md">419</h1>
        <h2 class="mt-6 text-2xl font-bold text-gray-900 tracking-tight sm:text-3xl">Page Expired</h2>
        <p class="mt-4 text-base text-gray-600">Your session has expired. Please refresh the page and try again.</p>
        
        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
            <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh Page
            </button>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors duration-200 shadow-sm">
                Go to Login
            </a>
        </div>
    </div>
</x-guest-layout>
