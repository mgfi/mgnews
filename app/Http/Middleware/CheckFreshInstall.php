<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckFreshInstall
{
    public function handle(Request $request, Closure $next)
    {
        if (!cache()->get('app_installed', false) && User::count() === 0) {
            if (!$request->is('install*')) {
                return redirect()->route('install.start');
            }
        }

        return $next($request);
    }
}
