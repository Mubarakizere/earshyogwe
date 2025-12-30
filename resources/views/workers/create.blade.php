<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Add Worker</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('workers.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                <input type="text" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            @if($churches->count() > 1)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Church <span class="text-red-500">*</span></label>
                                    <select name="church_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        <option value="">Select Church</option>
                                        @foreach($churches as $church)
                                            <option value="{{ $church->id }}">{{ $church->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="church_id" value="{{ $churches->first()->id }}">
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                <select name="department_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Position <span class="text-red-500">*</span></label>
                            <input type="text" name="position" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Employment Date <span class="text-red-500">*</span></label>
                                <input type="date" name="employment_date" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Birth Date</label>
                                <input type="date" name="birth_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Retirement Age <span class="text-red-500">*</span></label>
                                <input type="number" name="retirement_age" value="60" min="50" max="70" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('workers.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg shadow-md">Add Worker</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
