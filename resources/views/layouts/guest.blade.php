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
    <body class="font-sans antialiased text-gray-900 bg-white">
        <div class="min-h-screen flex">
            <!-- Left Side: Brand Panel (Hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 bg-brand-900 relative justify-center items-center overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-brand-800 to-brand-950 opacity-90"></div>
                
                <!-- Content -->
                <div class="relative z-10 text-center px-12">
                     <div class="mb-6 inline-block p-4 rounded-full bg-white/10 backdrop-blur-sm border border-white/20">
                        <img src="/storage/logo/logo.jpg" alt="Logo" class="w-20 h-20 object-contain rounded-full">
                    </div>
                    <h1 class="text-4xl font-extrabold text-white tracking-tight mb-4">EAR SHYOGWE DIOCESE</h1>
                    <p class="text-brand-100 text-lg max-w-md mx-auto leading-relaxed">
                        Secure Enterprise Management System.<br>Excellence in Administration.
                    </p>
                    <div class="mt-12 text-sm text-brand-300">
                        &copy; {{ date('Y') }} EAR SHYOGWE DIOCESE. All rights reserved.
                    </div>
                </div>
            </div>

            <!-- Right Side: Form Area -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 bg-white relative">
                <!-- Mobile Logo (Visible only on small screens) -->
                <div class="lg:hidden mb-8">
                    <a href="/" class="flex flex-col items-center">
                        <img src="/storage/logo/logo.jpg" alt="Logo" class="w-12 h-12 object-contain">
                    </a>
                </div>

                <div class="w-full max-w-md space-y-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
        
        <!-- Custom Styles to override browser defaults -->
        <style>
            /* Remove chrome autocomplete background color */
            input:-webkit-autofill,
            input:-webkit-autofill:hover, 
            input:-webkit-autofill:focus, 
            input:-webkit-autofill:active{
                -webkit-box-shadow: 0 0 0 30px white inset !important;
                -webkit-text-fill-color: #1f2937 !important;
            }
        </style>
    </body>
</html>
