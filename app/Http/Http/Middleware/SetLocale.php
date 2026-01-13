<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): \Illuminate\Http\Response  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $availableLocales = config('translations.supported_locales', ['pl', 'en']);

        if ($locale = Session::get('locale')) {
            if (in_array($locale, $availableLocales)) {
                App::setLocale($locale);
            }
        } else {
            $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

            if (in_array($browserLocale, $availableLocales)) {
                $locale = $browserLocale;  // przypisz wykryty język
            } else {
                $locale = config('app.fallback_locale', 'en');
            }

            Session::put('locale', $locale);  // **tu zapisujemy do sesji**
            App::setLocale($locale);
        }

        return $next($request);
    }
}