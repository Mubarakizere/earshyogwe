<!-- Mobile Overlay -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-30 md:hidden" style="display: none;"></div>

<!-- Sidebar -->
<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed inset-y-0 left-0 z-40 w-64 bg-brand-900 border-r border-brand-800 transition-transform duration-300 ease-in-out md:static md:flex md:flex-col md:flex-shrink-0">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-brand-950 shadow-sm">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white font-bold text-lg tracking-tight">
            <img src="/storage/logo/logo.jpg" alt="Logo" class="w-8 h-8 object-contain">
            <span class="hidden md:block truncate">EAR SHYOGWE DIOCESE</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 flex flex-col overflow-y-auto pt-5 pb-4 px-3 space-y-1">
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            {{ __('Dashboard') }}
        </x-sidebar-link>

        @hasrole('boss')
            <x-sidebar-link :href="route('giving-types.index')" :active="request()->routeIs('giving-types.*')">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                {{ __('Giving Types') }}
            </x-sidebar-link>
        @endhasrole

        @can('enter givings')
             <x-sidebar-link :href="route('givings.index')" :active="request()->routeIs('givings.*')">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ __('Givings') }}
            </x-sidebar-link>
        @endcan

        @can('verify diocese receipt')
             <x-sidebar-link :href="route('diocese.transfers.index')" :active="request()->routeIs('diocese.transfers.*')">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                {{ __('Incoming Transfers') }}
            </x-sidebar-link>
        @endcan

        @hasrole('boss')
            <x-sidebar-link :href="route('expense-categories.index')" :active="request()->routeIs('expense-categories.*')">
                 <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                {{ __('Categories') }}
            </x-sidebar-link>
        @endhasrole

        @canany(['view all expenses', 'view assigned expenses', 'view own expenses'])
        <x-sidebar-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
            {{ __('Expenses') }}
        </x-sidebar-link>
        @endcanany
        
        @canany(['view all evangelism', 'view assigned evangelism', 'view own evangelism'])
        <x-sidebar-link :href="route('evangelism-reports.index')" :active="request()->routeIs('evangelism-reports.*')">
             <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
            {{ __('Evangelism') }}
        </x-sidebar-link>
        @endcanany

        @canany(['view all activities', 'view assigned activities', 'view own activities'])
         <x-sidebar-link :href="route('activities.index')" :active="request()->routeIs('activities.*')">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            {{ __('Activities') }}
        </x-sidebar-link>
        @endcanany

        @canany(['view all departments', 'create departments'])
         <x-sidebar-link :href="route('departments.index')" :active="request()->routeIs('departments.*')">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            {{ __('Departments') }}
        </x-sidebar-link>
        @endcanany

         @canany(['view all churches', 'view assigned churches'])
        <x-sidebar-link :href="route('churches.index')" :active="request()->routeIs('churches.*')">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
            {{ __('Churches') }}
        </x-sidebar-link>
        @endcanany

        @canany(['manage all workers', 'manage assigned workers', 'manage own workers'])
        <x-sidebar-link :href="route('workers.index')" :active="request()->routeIs('workers.*')">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            {{ __('HR') }}
        </x-sidebar-link>
        @endcanany

        @canany(['view all churches', 'view assigned churches', 'view own church'])
        <x-sidebar-link :href="route('attendances.index')" :active="request()->routeIs('attendances.*')">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
            {{ __('Attendance') }}
        </x-sidebar-link>
        @endcanany

        <!-- Population (Members Registry) -->
        @canany(['view all members', 'view assigned members', 'view own members'])
        <x-sidebar-link :href="route('members.index')" :active="request()->routeIs('members.*')">
            <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Population
        </x-sidebar-link>
        @endcanany

        <!-- Admin Section -->
        @hasrole('boss')
            <div class="mt-8 mb-2 px-3 text-xs font-semibold text-brand-300 uppercase tracking-wider">
                Administration
            </div>
            <x-sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                 <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                {{ __('Users') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                {{ __('Roles') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                {{ __('Logs') }}
            </x-sidebar-link>
        @endhasrole
    </div>

    <!-- User Profile (Bottom Sidebar) -->
    <div class="border-t border-brand-800 p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                 <img class="h-10 w-10 rounded-full border border-brand-500 object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white group-hover:text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs font-medium text-brand-300 group-hover:text-gray-700">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</div>
