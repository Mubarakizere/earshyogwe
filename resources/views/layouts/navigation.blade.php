<nav x-data="{ open: false }" class="bg-brand-700 border-b border-brand-800 shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-white font-bold text-xl tracking-tight flex items-center gap-2">
                        <svg class="w-8 h-8 text-white fill-current" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        EAR SHYOGWE DIOCESE
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-brand-100 hover:text-white border-transparent hover:border-white focus:text-white focus:border-white transition duration-150 ease-in-out">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @can('manage giving types')
                        <x-nav-link :href="route('giving-types.index')" :active="request()->routeIs('giving-types.*')" class="text-brand-100 hover:text-white border-transparent hover:border-white transition duration-150 ease-in-out">
                            {{ __('Giving Types') }}
                        </x-nav-link>
                    @endcan
                    
                    @can('enter givings')
                        <x-nav-link :href="route('givings.index')" :active="request()->routeIs('givings.*')" class="text-brand-100 hover:text-white border-transparent hover:border-white transition duration-150 ease-in-out">
                            {{ __('Givings') }}
                        </x-nav-link>
                    @endcan
                    
                    @can('manage expense categories')
                        <x-nav-link :href="route('expense-categories.index')" :active="request()->routeIs('expense-categories.*')" class="text-brand-100 hover:text-white border-transparent hover:border-white transition duration-150 ease-in-out">
                            {{ __('Categories') }}
                        </x-nav-link>
                    @endcan
                    
                    <x-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')" class="text-brand-100 hover:text-white border-transparent hover:border-white transition duration-150 ease-in-out">
                        {{ __('Expenses') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('evangelism-reports.index')" :active="request()->routeIs('evangelism-reports.*')" class="text-brand-100 hover:text-white border-transparent hover:border-white transition duration-150 ease-in-out">
                        {{ __('Evangelism') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('activities.index')" :active="request()->routeIs('activities.*')" class="text-brand-100 hover:text-white border-transparent hover:border-white transition duration-150 ease-in-out">
                        {{ __('Activities') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('workers.index')" :active="request()->routeIs('workers.*')" class="text-brand-100 hover:text-white border-transparent hover:border-white transition duration-150 ease-in-out">
                        {{ __('HR') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('attendances.index')" :active="request()->routeIs('attendances.*')" class="text-brand-100 hover:text-white border-transparent hover:border-white transition duration-150 ease-in-out">
                        {{ __('Attendance') }}
                    </x-nav-link>
                    <x-nav-link :href="route('population-censuses.index')" :active="request()->routeIs('population-censuses.*')" class="text-brand-100 hover:text-white border-transparent hover:border-white transition duration-150 ease-in-out">
                        {{ __('Census') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Notifications Dropdown -->
                <div class="text-white mr-4">
                    <x-notifications-dropdown /> 
                </div>

                @canany(['manage users', 'view activity logs'])
                    <div class="hidden sm:flex sm:items-center sm:ms-6 border-l border-brand-600 pl-6 h-8 my-auto">
                        @can('manage users')
                            <a href="{{ route('users.index') }}" class="text-sm font-medium text-brand-100 hover:text-white mr-4 transition duration-150 ease-in-out">Users</a>
                        @endcan
                        @can('view activity logs')
                            <a href="{{ route('activity-logs.index') }}" class="text-sm font-medium text-brand-100 hover:text-white transition duration-150 ease-in-out">Logs</a>
                        @endcan
                    </div>
                @endcanany
                
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-brand-700 bg-white hover:bg-gray-50 hover:text-brand-800 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                            <div class="font-bold">{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-brand-100 hover:text-white hover:bg-brand-600 focus:outline-none focus:bg-brand-600 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-brand-800 border-t border-brand-900">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-brand-700 focus:bg-brand-700">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @can('manage giving types')
                <x-responsive-nav-link :href="route('giving-types.index')" :active="request()->routeIs('giving-types.*')" class="text-brand-100 hover:text-white hover:bg-brand-700">
                    {{ __('Giving Types') }}
                </x-responsive-nav-link>
            @endcan
            
            @can('enter givings')
                <x-responsive-nav-link :href="route('givings.index')" :active="request()->routeIs('givings.*')" class="text-brand-100 hover:text-white hover:bg-brand-700">
                    {{ __('Givings') }}
                </x-responsive-nav-link>
            @endcan

            <x-responsive-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')" class="text-brand-100 hover:text-white hover:bg-brand-700">
                {{ __('Expenses') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('population-censuses.index')" :active="request()->routeIs('population-censuses.*')" class="text-brand-100 hover:text-white hover:bg-brand-700">
                {{ __('Population Census') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-brand-900 bg-brand-900/50">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-brand-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-brand-100 hover:text-white hover:bg-brand-700">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-brand-100 hover:text-white hover:bg-brand-700">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
