<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Edit Role: ' . $role->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8">
                <form method="POST" action="{{ route('roles.update', $role) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-6 max-w-md">
                        <x-input-label for="name" :value="__('Role Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $role->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <hr class="my-6 border-gray-100">

                    <h3 class="text-lg font-bold text-gray-800 mb-4">Assign Permissions</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($groupedPermissions as $groupName => $permissions)
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="font-bold text-sm text-gray-700 uppercase mb-3 border-b border-gray-200 pb-2">{{ $groupName }}</h4>
                                <div class="space-y-2">
                                    @foreach($permissions as $permission)
                                        <label class="flex items-start">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                                class="rounded border-gray-300 text-brand-600 shadow-sm focus:border-brand-300 focus:ring focus:ring-brand-200 focus:ring-opacity-50 mt-1"
                                                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                            <span class="ml-2 text-sm text-gray-600">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end pt-8">
                        <a href="{{ route('roles.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4 flex items-center">Cancel</a>
                        <x-primary-button>
                            {{ __('Update Role') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
