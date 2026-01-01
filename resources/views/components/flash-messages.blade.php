@if (session('success'))
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md relative" role="alert">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-green-700 hover:text-green-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
        <div x-data="{ show: true }" x-show="show" x-transition class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md relative" role="alert">
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-red-700 hover:text-red-900">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    </div>
@endif
