<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        if (Session::has('locale')) {
            $locale = Session::get('locale');
        } elseif ($request->hasHeader("Accept-Language")) {
            $locale = $request->header("Accept-Language");
        }
    
        // Validate if the locale is supported
        $supportedLocales = ['en', 'en_US', 'ar_SA', 'bn_BD']; // Add all your supported locales here
        if ($locale && in_array($locale, $supportedLocales)) {
            app()->setLocale($locale);
        } else {
            app()->setLocale('en'); // Fallback to default locale
        }
            
        return $next($request);
    }
}
