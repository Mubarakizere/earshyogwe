<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if locale is stored in session
        if (session()->has('locale')) {
            $locale = session('locale');
            
            // Verify it's a valid locale
            if (in_array($locale, config('app.available_locales', ['en']))) {
                app()->setLocale($locale);
            }
        }
        
        return $next($request);
    }
}
