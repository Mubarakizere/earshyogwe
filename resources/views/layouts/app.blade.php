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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 h-screen overflow-hidden flex" x-data="{ sidebarOpen: false }">
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
                        &copy; {{ date('Y') }} EARS HYOGWE. All rights reserved.
                    </footer>
                </div>
            </main>
        </div>
    </body>
</html>
