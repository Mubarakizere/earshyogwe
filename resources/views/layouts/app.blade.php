<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- SEO Meta Tags -->
        <meta name="description" content="SMRS Enterprise Management System">
        <meta name="keywords" content="church, diocese, management, SMRS">
        <meta name="author" content="SMRS">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Leaflet.js for Maps -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
              crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
                crossorigin=""></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" type="image/jpeg" href="{{ asset('storage/logo/logo.jpg') }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/logo/logo.jpg') }}">
        
        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#1e3a8a">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="SMRS">
        <link rel="manifest" href="{{ asset('manifest.json') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- PWA Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then((reg) => console.log('Service Worker registered', reg))
                        .catch((err) => console.log('Service Worker registration failed', err));
                });
            }
        </script>

        <!-- OneSignal Push Notifications -->
        <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
        <script>
            window.OneSignalDeferred = window.OneSignalDeferred || [];
            OneSignalDeferred.push(async function(OneSignal) {
                await OneSignal.init({
                    appId: "7dbce2db-7a28-40c4-a408-b6f8f58e1274",
                });
                
                // Auto-register user ID for targeted notifications
                @auth
                OneSignal.login("{{ auth()->id() }}");
                @endauth
            });
        </script>

        <!-- Google Translate Integration -->
        <script type="text/javascript">
            function googleTranslateElementInit() {
                console.log('Initializing Google Translate widget...');
                new google.translate.TranslateElement({
                    pageLanguage: 'en',
                    includedLanguages: 'en,fr,rw,sw',
                    layout: google.translate.TranslateElement.InlineLayout.VERTICAL
                }, 'google_translate_element');
                console.log('Google Translate widget initialized');
            }
        </script>
        <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

        <style>
            /* Aggressively hide ALL Google Translate UI elements */
            .goog-te-banner-frame,
            .goog-te-banner-frame.skiptranslate,
            .skiptranslate,
            iframe.skiptranslate,
            .goog-te-ftab,
            .goog-te-balloon-frame {
                display: none !important;
                visibility: hidden !important;
                opacity: 0 !important;
                height: 0 !important;
                position: absolute !important;
                top: -9999px !important;
            }
            
            body {
                top: 0px !important;
                position: static !important;
            }
            
            /* Position Google Translate element off-screen but keep it in DOM */
            #google_translate_element {
                position: fixed !important;
                top: -9999px !important;
                left: -9999px !important;
                z-index: -1 !important;
                width: 1px !important;
                height: 1px !important;
                overflow: hidden !important;
            }
            
            .goog-te-gadget {
                font-size: 0 !important;
                color: transparent !important;
            }
            
            .goog-te-gadget .goog-te-combo {
                margin: 0 !important;
            }
            
            /* Prevent translation from breaking layout */
            .translated-ltr {
                margin-top: 0 !important;
            }
            
            /* Hide the Google Translate top frame */
            body > .skiptranslate {
                display: none !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 h-screen overflow-hidden flex" 
          x-data="{ sidebarOpen: false, loading: true }" 
          x-init="window.onload = () => { setTimeout(() => loading = false, 800) }">

        <!-- Hidden Google Translate Element (positioned off-screen) -->
        <div id="google_translate_element"></div>

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
                    <p class="text-brand-800 font-bold tracking-widest text-xs uppercase mb-1">SMRS</p>
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
                        &copy; {{ date('Y') }} SMRS. All rights reserved.
                    </footer>
                </div>
            </main>
        </div>
        
        @stack('scripts')
    </body>
</html>
