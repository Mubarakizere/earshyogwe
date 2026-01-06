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
                        <svg class="w-20 h-20 text-white fill-current" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-extrabold text-white tracking-tight mb-4">EARS HYOGWE</h1>
                    <p class="text-brand-100 text-lg max-w-md mx-auto leading-relaxed">
                        Secure Enterprise Management System.<br>Excellence in Administration.
                    </p>
                    <div class="mt-12 text-sm text-brand-300">
                        &copy; {{ date('Y') }} EARS HYOGWE. All rights reserved.
                    </div>
                </div>
            </div>

            <!-- Right Side: Form Area -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 bg-white relative">
                <!-- Mobile Logo (Visible only on small screens) -->
                <div class="lg:hidden mb-8">
                    <a href="/" class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-brand-700 fill-current" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
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
