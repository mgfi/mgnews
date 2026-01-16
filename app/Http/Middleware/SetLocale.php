<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {

        if (session()->has('locale')) {
            App::setLocale(session('locale'));
            return $next($request);
        }


        $browserLocale = substr($request->getPreferredLanguage(), 0, 2);

        $locale = in_array($browserLocale, ['pl', 'en'])
            ? $browserLocale
            : config('app.locale');

        session(['locale' => $locale]);
        App::setLocale($locale);

        return $next($request);
    }
}
