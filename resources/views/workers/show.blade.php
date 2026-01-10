<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Worker Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $worker->full_name }}</h3>
                            <p class="text-lg text-gray-600">{{ $worker->position }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('workers.edit', $worker) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                                Edit Profile
                            </a>
                            <a href="{{ route('workers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg shadow transition">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Personal Info -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h4 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Personal Information</h4>
                            <dl class="space-y-3">
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $worker->email }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $worker->phone ?? 'N/A' }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">
                                        {{ $worker->birth_date ? $worker->birth_date->format('M d, Y') : 'N/A' }}
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="text-sm font-bold {{ $worker->status === 'active' ? 'text-green-600' : 'text-gray-500' }} col-span-2 uppercase">
                                        {{ $worker->status }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Church & Department -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h4 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Organization</h4>
                            <dl class="space-y-3">
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Church</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $worker->church->name ?? 'N/A' }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Department</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $worker->department->name ?? 'N/A' }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Hired On</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">
                                        {{ $worker->employment_date ? $worker->employment_date->format('M d, Y') : 'N/A' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Retirement Info -->
                    @if($worker->retirement_status !== 'unknown')
                        @php
                            $bgClass = 'bg-blue-50 border-blue-200';
                            $titleClass = 'text-blue-800';
                            $statusTitle = 'Retirement Outlook';
                            
                            if ($worker->retirement_status === 'overdue') {
                                $bgClass = 'bg-red-50 border-red-200';
                                $titleClass = 'text-red-800';
                                $statusTitle = 'Retirement Overdue';
                            } elseif ($worker->retirement_status === 'soon') {
                                $bgClass = 'bg-yellow-50 border-yellow-200';
                                $titleClass = 'text-yellow-800';
                                $statusTitle = 'Retirement Approaching';
                            }
                        @endphp
                        
                        <div class="mt-8 {{ $bgClass }} p-6 rounded-lg border">
                            <h4 class="text-lg font-semibold mb-2 {{ $titleClass }}">{{ $statusTitle }}</h4>
                            <p class="text-gray-700">
                                Planning for retirement at age <strong>{{ $worker->retirement_age }}</strong>.
                                @if($worker->retirement_status === 'overdue')
                                    <span class="font-bold text-red-600 block mt-1">
                                        Overdue by {{ $worker->years_overdue }} years.
                                    </span>
                                @elseif($worker->retirement_status === 'soon')
                                    <span class="font-bold text-yellow-600 block mt-1">
                                        Retiring in {{ $worker->years_to_retirement }} years.
                                    </span>
                                @else
                                    <span>Expected retirement in <strong>{{ $worker->years_to_retirement }}</strong> years.</span>
                                @endif
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
