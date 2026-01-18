<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        if (
            Auth::check() &&
            Auth::user()->must_change_password
        ) {
            // pozwól na change password + submit + logout
            if ($request->routeIs([
                'password.change',
                'password.update.force',
                'logout',
            ])) {
                return $next($request);
            }

            // flash tylko raz – nie nadpisuj walidacji
            if (!session()->has('warning')) {
                session()->flash(
                    'warning',
                    __('You must change your password.')
                );
            }

            return redirect()->route('password.change');
        }

        return $next($request);
    }
}
