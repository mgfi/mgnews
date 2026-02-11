<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetPanelUi
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->routeIs('admin.*')) {
            view()->share([
                'navbar'  => 'partials.navbar-admin',
                'sidebar' => 'partials.sidebar-admin',
            ]);
        }

        if ($request->routeIs('operator.*')) {
            view()->share([
                'navbar'  => 'partials.navbar-operator',
                'sidebar' => 'partials.sidebar-operator',
            ]);
        }

        return $next($request);
    }
}
