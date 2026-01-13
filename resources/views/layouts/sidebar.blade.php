<!-- Mobile Overlay -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-30 md:hidden" style="display: none;"></div>

<!-- Sidebar -->
<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" class="fixed inset-y-0 left-0 z-40 w-64 bg-brand-900 border-r border-brand-800 transition-transform duration-300 ease-in-out md:static md:flex md:flex-col md:flex-shrink-0 text-brand-100">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-brand-950 shadow-sm border-b border-brand-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-white font-bold text-lg tracking-tight">
            <img src="/storage/logo/logo.jpg" alt="Logo" class="w-8 h-8 rounded-full border border-brand-700">
            <span class="hidden md:block truncate text-white">EAR SHYOGWE</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 flex flex-col overflow-y-auto pt-5 pb-4 px-3 space-y-2 custom-scrollbar">
        <!-- Dashboard -->
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            {{ __('Dashboard') }}
        </x-sidebar-link>

        <!-- Ministry Management Dropdown -->
        <div x-data="{ open: {{ request()->routeIs('churches.*') || request()->routeIs('departments.*') || request()->routeIs('activities.*') || request()->routeIs('evangelism-reports.*') || request()->routeIs('attendances.*') || request()->routeIs('members.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" class="flex items-center w-full px-2 py-2 text-sm font-medium rounded-md hover:bg-brand-800 hover:text-white group transition-colors focus:outline-none" :class="{ 'bg-brand-800 text-white': open, 'text-brand-200': !open }">
                <svg class="w-5 h-5 mr-3 text-brand-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                <span class="flex-1 text-left">Ministry</span>
                <svg :class="{ 'rotate-90': open, 'text-brand-400': !open, 'text-white': open }" class="w-4 h-4 ml-auto transform transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-1 pl-9">
                @canany(['view all churches', 'view assigned churches'])
                    <a href="{{ route('churches.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('churches.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Parishes
                    </a>
                @endcanany
                @canany(['view all departments', 'create departments'])
                    <a href="{{ route('departments.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('departments.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Directorates
                    </a>
                @endcanany
                @canany(['view all activities', 'view assigned activities', 'view own activities'])
                    <a href="{{ route('activities.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('activities.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Activities
                    </a>
                @endcanany
                @canany(['view all evangelism', 'view assigned evangelism', 'view own evangelism'])
                    <a href="{{ route('evangelism-reports.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('evangelism-reports.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Evangelism
                    </a>
                @endcanany
                @canany(['view all churches', 'view assigned churches', 'view own church'])
                    <a href="{{ route('attendances.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('attendances.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Attendance
                    </a>
                @endcanany
                 @canany(['view all members', 'view assigned members', 'view own members'])
                    <a href="{{ route('members.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('members.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Members
                    </a>
                @endcanany
            </div>
        </div>

        <!-- Finance Management Dropdown -->
         <div x-data="{ open: {{ request()->routeIs('givings.*') || request()->routeIs('diocese.transfers.*') || request()->routeIs('expenses.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" class="flex items-center w-full px-2 py-2 text-sm font-medium rounded-md hover:bg-brand-800 hover:text-white group transition-colors focus:outline-none" :class="{ 'bg-brand-800 text-white': open, 'text-brand-200': !open }">
                <svg class="w-5 h-5 mr-3 text-brand-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="flex-1 text-left">Finance</span>
                <svg :class="{ 'rotate-90': open, 'text-brand-400': !open, 'text-white': open }" class="w-4 h-4 ml-auto transform transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-1 pl-9">
                @can('enter givings')
                    <a href="{{ route('givings.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('givings.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Offerings
                    </a>
                @endcan
                @can('verify diocese receipt')
                    <a href="{{ route('diocese.transfers.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('diocese.transfers.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Transfers
                    </a>
                @endcan
                @canany(['view all expenses', 'view assigned expenses', 'view own expenses'])
                    <a href="{{ route('expenses.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('expenses.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Expenses
                    </a>
                @endcanany
            </div>
        </div>

        <!-- Administration Dropdown -->
        <div x-data="{ open: {{ request()->routeIs('workers.*') || request()->routeIs('institutions.*') || request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('activity-logs.*') || request()->routeIs('giving-types.*') || request()->routeIs('expense-categories.*') || request()->routeIs('service-types.*') ? 'true' : 'false' }} }" class="space-y-1">
             <button @click="open = !open" class="flex items-center w-full px-2 py-2 text-sm font-medium rounded-md hover:bg-brand-800 hover:text-white group transition-colors focus:outline-none" :class="{ 'bg-brand-800 text-white': open, 'text-brand-200': !open }">
                <svg class="w-5 h-5 mr-3 text-brand-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <span class="flex-1 text-left">Administration</span>
                <svg :class="{ 'rotate-90': open, 'text-brand-400': !open, 'text-white': open }" class="w-4 h-4 ml-auto transform transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="open" x-collapse class="space-y-1 pl-9">
                 @canany(['manage all workers', 'manage assigned workers', 'manage own workers'])
                    <a href="{{ route('workers.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('workers.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        HR / Workers
                    </a>
                @endcanany
                
                @can('manage institutions')
                    <a href="{{ route('institutions.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('institutions.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Institutions
                    </a>
                @endcan

                 @can('manage users')
                    <a href="{{ route('users.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('users.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Users
                    </a>
                @endcan
                @can('manage roles')
                    <a href="{{ route('roles.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('roles.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Roles
                    </a>
                @endcan
                @can('view activity logs')
                    <a href="{{ route('activity-logs.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('activity-logs.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Audit Logs
                    </a>
                @endcan
                @can('manage giving types')
                     <a href="{{ route('giving-types.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('giving-types.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Offerings Types
                    </a>
                @endcan
                @can('manage expense categories')
                     <a href="{{ route('expense-categories.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('expense-categories.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Expense Cats
                    </a>
                @endcan
                @can('manage service types')
                     <a href="{{ route('service-types.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md hover:text-white hover:bg-brand-800 {{ request()->routeIs('service-types.*') ? 'text-white bg-brand-800' : 'text-brand-200' }}">
                        Service Types
                    </a>
                @endcan
            </div>
        </div>

    </div>

    <!-- User Profile (Bottom Sidebar) -->
    <div class="border-t border-brand-800 p-4 bg-brand-950">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                 <img class="h-10 w-10 rounded-full border border-brand-600 object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}">
            </div>
            <div class="ml-3 overflow-hidden">
                <p class="text-sm font-medium text-white group-hover:text-gray-900 truncate">{{ Auth::user()->name }}</p>
                <div class="text-xs font-medium text-brand-300 group-hover:text-brand-100 truncate">
                    @if(Auth::user()->roles->count() > 0)
                        {{ ucfirst(Auth::user()->roles->first()->name) }}
                    @else
                        User
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
