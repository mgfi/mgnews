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
            // allow change password + submit + logout
            if ($request->routeIs([
                'password.change',
                'password.update.force',
                'logout',
            ])) {
                return $next($request);
            }

            // GUARD: Livewire / AJAX / API
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('alerts.force_password_change'),
                ], 423);
            }

            // flash only once – do not override validation
            if (!session()->has('warning')) {
                session()->flash(
                    'warning',
                    __('alerts.force_password_change')
                );
            }

            return redirect()->route('password.change');
        }

        return $next($request);
    }
}
