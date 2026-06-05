<x-guest-layout>
    <div class="text-center py-10">
        <h1 class="text-9xl font-black text-brand-600 drop-shadow-md">404</h1>
        <h2 class="mt-6 text-2xl font-bold text-gray-900 tracking-tight sm:text-3xl">Page Not Found</h2>
        <p class="mt-4 text-base text-gray-600">Sorry, we couldn't find the page you're looking for.</p>
        
        <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Go back home
            </a>
            <button onclick="window.history.back()" class="inline-flex items-center justify-center px-5 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors duration-200 shadow-sm">
                Go back
            </button>
        </div>
    </div>
</x-guest-layout>
