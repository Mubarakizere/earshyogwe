<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Record Population Census</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-8">
                <form action="{{ route('population-censuses.store') }}" method="POST">
                    @csrf

                    @if($errors->any())
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-6">
                        <!-- Church & Period Selection -->
                        <div class="grid grid-cols-2 gap-4">
                            @if($churches->count() > 1)
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Church <span class="text-red-500">*</span></label>
                                    <select name="church_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        @foreach($churches as $church)
                                            <option value="{{ $church->id }}">{{ $church->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="church_id" value="{{ auth()->user()->church_id }}">
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Census Year <span class="text-red-500">*</span></label>
                                <select name="year" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    @for($y = date('Y'); $y >= 2023; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Period <span class="text-red-500">*</span></label>
                                <select name="period" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="annual">Annual Census</option>
                                    <option value="q1">Quarter 1</option>
                                    <option value="q2">Quarter 2</option>
                                    <option value="q3">Quarter 3</option>
                                    <option value="q4">Quarter 4</option>
                                </select>
                            </div>
                        </div>

                        <hr>

                        <!-- Member Counts -->
                        <h3 class="text-lg font-semibold text-gray-800">Member Counts</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Men (Adults) <span class="text-red-500">*</span></label>
                                <input type="number" name="men_count" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="0">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Women (Adults) <span class="text-red-500">*</span></label>
                                <input type="number" name="women_count" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="0">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Youth (Young Adults) <span class="text-red-500">*</span></label>
                                <input type="number" name="youth_count" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="0">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Children (Sunday School) <span class="text-red-500">*</span></label>
                                <input type="number" name="children_count" min="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="0">
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Infants (0-3 Years) <span class="text-gray-400 text-xs">(Optional)</span></label>
                                <input type="number" name="infants_count" min="0" value="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="0">
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-4">
                            <a href="{{ route('population-censuses.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md">Submit Census Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
