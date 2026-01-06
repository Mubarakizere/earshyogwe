<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Member Registry') }}
            </h2>
            <a href="{{ route('members.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-700 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-brand-800 focus:bg-brand-800 active:bg-brand-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add Member
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filter/Stats Bar could go here -->

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Church</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demographics</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($members as $member)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $member->name }}</div>
                                                <div class="text-xs text-brand-600">{{ $member->sex }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600">{{ $member->church->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $member->age ? $member->age . ' yrs' : 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $member->education_level ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $member->marital_status }}
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">{{ $member->baptism_status }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->church_group ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('members.edit', $member) }}" class="text-brand-600 hover:text-brand-900 mr-3">Edit</a>
                                        <!-- Add Delete Button later -->
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        No members found. Start by adding one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $members->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
