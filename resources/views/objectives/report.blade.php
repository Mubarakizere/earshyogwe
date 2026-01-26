<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-3">
                    {{ __('Submit Activity Report') }}
                    <span class="px-3 py-1 text-xs font-bold tracking-wider uppercase bg-purple-100 text-purple-700 rounded-full border border-purple-200">
                        Reporting
                    </span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Submit progress updates and results for this objective.</p>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('objectives.show', $objective) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Details
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Pro Header Card -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-t-lg shadow-lg p-6 text-white overflow-hidden relative">
                <div class="relative z-10">
                    <p class="text-purple-100 text-xs font-bold uppercase tracking-wider mb-1">Current Objective</p>
                    <h3 class="text-2xl font-bold">{{ $objective->name }}</h3>
                    <div class="flex items-center gap-4 mt-2 text-purple-100 text-sm">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            {{ $objective->church->name }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ $objective->department->name }}
                        </span>
                    </div>
                </div>
                <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-4 translate-y-4">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                    </svg>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-b-lg border-x border-b border-gray-200">
                <form action="{{ route('objectives.report.store', $objective) }}" method="POST" class="p-8" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        {{-- LEFT COLUMN --}}
                        <div class="space-y-6">
                            <div>
                                <label for="activities_description" class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    {{ __('Activities Performed') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea name="activities_description" id="activities_description" rows="5" 
                                    class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm transition-colors"
                                    placeholder="Describe what was done..." required>{{ old('activities_description') }}</textarea>
                                @error('activities_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="quantity" class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        {{ __('Indicator') }} <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" name="quantity" id="quantity" step="0.01" 
                                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm transition-colors pr-12"
                                            placeholder="0" required value="{{ old('quantity') }}">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <span class="text-gray-400 text-xs font-medium">{{ $objective->target_unit ?? 'Qty' }}</span>
                                        </div>
                                    </div>
                                    @error('quantity')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="report_date" class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        {{ __('Date of Activity') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="report_date" id="report_date" 
                                        class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm transition-colors"
                                        value="{{ old('report_date', date('Y-m-d')) }}" required>
                                    @error('report_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN --}}
                        <div class="space-y-6">
                            <div>
                                <label for="results_outcome" class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    {{ __('Results & Outcomes') }} <span class="text-red-500">*</span>
                                </label>
                                <textarea name="results_outcome" id="results_outcome" rows="5" 
                                    class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm transition-colors"
                                    placeholder="What was the impact/result?" required>{{ old('results_outcome') }}</textarea>
                                @error('results_outcome')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="budget_spent" class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        {{ __('Cost (RWF)') }}
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <span class="text-gray-400 text-xs">RWF</span>
                                        </div>
                                        <input type="number" name="budget_spent" id="budget_spent" step="0.01" 
                                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm transition-colors pl-12"
                                            placeholder="0" value="{{ old('budget_spent') }}">
                                    </div>
                                    @error('budget_spent')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="location" class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">
                                        {{ __('Location') }}
                                    </label>
                                    <input type="text" name="location" id="location" 
                                        class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm transition-colors"
                                        value="{{ old('location') }}" placeholder="e.g. Parish Hall">
                                    @error('location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                    @error('location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">
                                    {{ __('Supporting Documents (Optional)') }}
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="documents" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-purple-500">
                                                <span>Upload files</span>
                                                <input id="documents" name="documents[]" type="file" class="sr-only" multiple accept=".pdf,.jpg,.jpeg,.png">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            PNG, JPG, PDF up to 5MB
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <label for="responsible_person" class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">
                            {{ __('Submitted By / Responsible Person') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="responsible_person" id="responsible_person" 
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm transition-colors"
                            value="{{ old('responsible_person', auth()->user()->name) }}" required>
                        @error('responsible_person')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-10 flex justify-end gap-3 border-t border-gray-100 pt-8">
                        <a href="{{ route('objectives.show', $objective) }}" class="px-6 py-3 bg-white border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg shadow-lg transform transition hover:scale-105">
                            {{ __('Submit Report') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Hint Card -->
            <div class="mt-6 p-4 bg-purple-50 border border-purple-100 rounded-lg flex gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-purple-900 leading-tight">Pro Tip</h4>
                    <p class="text-sm text-purple-700 mt-1">
                        Submitting regular reports helps automatically calculate the progress percentage for this objective on the main dashboard.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
