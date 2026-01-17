@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ __('Submit Report') }}</h1>
        <a href="{{ route('objectives.show', $objective) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100 flex items-center">
            <x-heroicon-o-arrow-left class="w-5 h-5 mr-1"/>
            {{ __('Back to Objective') }}
        </a>
    </div>

    <!-- Main Form Card matching the Image Layout -->
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="p-6 sm:p-10">
            <form action="{{ route('objectives.report.store', $objective) }}" method="POST">
                @csrf
                
                <!-- Layout: Two Columns to match the Key Image -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-8">
                    
                    <!-- LEFT COLUMN -->
                    <div class="space-y-8">
                        
                        <!-- Objective (Read Only) -->
                        <div>
                            <label class="block text-xl font-bold text-[#E91E63] mb-2">{{ __('Objective') }}</label>
                            <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-300 dark:border-gray-600 min-h-[100px] text-lg text-gray-800 dark:text-gray-200">
                                {{ $objective->name }}
                                <div class="text-sm text-gray-500 mt-2 italic">{{ $objective->objectives }}</div>
                            </div>
                        </div>

                        <!-- Activities (The core of the report) -->
                        <div>
                            <label for="activities_description" class="block text-xl font-bold text-[#E91E63] mb-2">{{ __('Activities') }}</label>
                            <textarea name="activities_description" id="activities_description" rows="5" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg"
                                placeholder="Describe the activities performed..." required>{{ old('activities_description') }}</textarea>
                            @error('activities_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity/Output -->
                        <div>
                            <label for="quantity" class="block text-lg font-bold text-[#E91E63] mb-2">{{ __('Quantity/Output') }}</label>
                            <div class="flex items-center">
                                <input type="number" name="quantity" id="quantity" step="0.01" 
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg"
                                    placeholder="0" required value="{{ old('quantity') }}">
                                <span class="ml-3 text-gray-500 font-medium whitespace-nowrap">{{ $objective->target_unit ?? 'Units' }}</span>
                            </div>
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div>
                            <label for="report_date" class="block text-lg font-bold text-[#E91E63] mb-2">{{ __('Date') }}</label>
                            <input type="date" name="report_date" id="report_date" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg"
                                value="{{ old('report_date', date('Y-m-d')) }}" required>
                            @error('report_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="space-y-8">
                        
                        <!-- Responsible Person -->
                        <div>
                            <label for="responsible_person" class="block text-sm font-bold text-[#E91E63] mb-2 text-right lg:text-left">{{ __('Responsible Person (Implementers)') }}</label>
                            <input type="text" name="responsible_person" id="responsible_person" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('responsible_person', auth()->user()->name) }}" required>
                            @error('responsible_person')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-bold text-[#E91E63] mb-2 text-right lg:text-left">{{ __('Location') }}</label>
                            <input type="text" name="location" id="location" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('location') }}" placeholder="e.g., Main Hall, Rubavu District...">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Results (Outcome) -->
                        <div>
                            <label for="results_outcome" class="block text-sm font-bold text-[#E91E63] mb-2 text-right lg:text-left">{{ __('Results (Outcome)') }}</label>
                            <textarea name="results_outcome" id="results_outcome" rows="4" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="What was the outcome?" required>{{ old('results_outcome') }}</textarea>
                            @error('results_outcome')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Budget -->
                        <div>
                            <label for="budget_spent" class="block text-sm font-bold text-[#E91E63] mb-2 text-right lg:text-left">{{ __('Budget / Cost (RWF)') }}</label>
                            <input type="number" name="budget_spent" id="budget_spent" step="0.01" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="0" value="{{ old('budget_spent') }}">
                            @error('budget_spent')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                    </div>
                </div>

                <div class="mt-10 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-[#E91E63] hover:bg-pink-600 text-white font-bold rounded-lg shadow-lg transform transition hover:scale-105">
                        {{ __('Submit Report') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
