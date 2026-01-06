<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Role Management') }}
            </h2>
            <a href="{{ route('roles.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-700 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-brand-800 focus:bg-brand-800 active:bg-brand-900 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Create New Role
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions Count</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($roles as $role)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 uppercase">{{ $role->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $role->permissions->count() }} permissions
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($role->name !== 'boss')
                                            <a href="{{ route('roles.edit', $role) }}" class="text-brand-600 hover:text-brand-900 mr-3">Edit Permissions</a>
                                            <!-- Simple Delete Form -->
                                            @if(!in_array($role->name, ['boss', 'pastor', 'archid']))
                                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-gray-400 italic">System Protected</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
