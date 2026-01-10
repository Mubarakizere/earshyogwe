<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Favicons -->
        <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 h-screen overflow-hidden flex" 
          x-data="{ sidebarOpen: false, loading: true }" 
          x-init="window.onload = () => { setTimeout(() => loading = false, 800) }">

        <!-- Global Pro Loader -->
        <div x-show="loading" 
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-50">
            <div class="relative flex flex-col items-center">
                <!-- Branding Ring Spinner -->
                <div class="absolute inset-0 animate-spin-slow rounded-full border-b-2 border-brand-200 w-32 h-32 opacity-20"></div>
                
                <!-- Logo Breathing Animation -->
                <div class="animate-pulse-slow relative z-10 p-2 bg-white rounded-full shadow-lg border border-gray-100">
                    <x-application-logo class="block h-20 w-auto fill-current text-gray-800" />
                </div>
                
                <!-- Loading Text -->
                <div class="mt-8 text-center animate-fade-in-up">
                    <p class="text-brand-800 font-bold tracking-widest text-xs uppercase mb-1">EAR SHYOGWE DIOCESE</p>
                    <div class="flex items-center justify-center space-x-1">
                        <div class="w-1.5 h-1.5 bg-brand-400 rounded-full animate-bounce delay-75"></div>
                        <div class="w-1.5 h-1.5 bg-brand-500 rounded-full animate-bounce delay-100"></div>
                        <div class="w-1.5 h-1.5 bg-brand-600 rounded-full animate-bounce delay-150"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden relative">
            <!-- Top Navigation -->
            @include('layouts.topbar')

            <!-- Page Heading (Optional) -->
            @isset($header)
                <div class="bg-white shadow-sm border-b border-gray-100 z-0 relative">
                    <div class="max-w-7xl mx-auto py-4 px-6 sm:px-8">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <!-- Flash Messages Container -->
            <div class="px-6 sm:px-8 mt-6">
                 @include('components.flash-messages')
            </div>

            <!-- Scrollable Main Content -->
            <main class="flex-1 overflow-y-auto p-6 sm:p-8 scroll-smooth">
                {{ $slot }}

                <div class="pb-6">
                    <footer class="text-center text-sm text-gray-400 mt-12 border-t border-gray-200 pt-6">
                        &copy; {{ date('Y') }} EAR SHYOGWE DIOCESE. All rights reserved.
                    </footer>
                </div>
            </main>
        </div>
    </body>
</html>
