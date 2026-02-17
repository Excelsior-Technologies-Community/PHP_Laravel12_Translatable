<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    // Set the application locale from session before processing the request
    public function handle($request, Closure $next)
    {
        // Check if a locale is stored in session
        if (session()->has('locale')) {
            // Apply the selected locale to the application
            App::setLocale(session('locale'));
        }

        // Continue the request lifecycle
        return $next($request);
    }
}
