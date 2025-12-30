<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Role -->
                            <div>
                                <x-input-label for="role" :value="__('Role')" />
                                <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required onchange="toggleChurchField()">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <!-- Church (Conditional) -->
                            <div id="church_field" style="{{ $user->hasRole('pastor') ? '' : 'display: none;' }}">
                                <x-input-label for="church_id" :value="__('Assigned Church')" />
                                <select id="church_id" name="church_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Select Church</option>
                                    @foreach($churches as $church)
                                        <option value="{{ $church->id }}" {{ $user->church_id == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Required for Pastor role</p>
                                <x-input-error :messages="$errors->get('church_id')" class="mt-2" />
                            </div>
                            
                            <!-- Profile Photo -->
                            <div class="col-span-2">
                                <x-input-label for="profile_photo" :value="__('Profile Photo')" />
                                <div class="flex items-center gap-4 mt-2">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        <img class="h-16 w-16 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                    </div>
                                    <input id="profile_photo" type="file" name="profile_photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                </div>
                                <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
                            </div>
                            
                            <!-- Password Change (Optional) -->
                            <div class="col-span-2 border-t pt-4 mt-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <x-input-label for="password" :value="__('New Password (Optional)')" />
                                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password</p>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>
    
                                    <div>
                                        <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button class="ml-4">
                                {{ __('Update User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleChurchField() {
            const role = document.getElementById('role').value;
            const churchField = document.getElementById('church_field');
            if (role === 'pastor') {
                churchField.style.display = 'block';
                document.getElementById('church_id').required = true;
            } else {
                churchField.style.display = 'none';
                document.getElementById('church_id').required = false;
            }
        }
    </script>
</x-app-layout>
