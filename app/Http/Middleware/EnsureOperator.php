<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureOperator
{
    public function handle($request, Closure $next)
    {
        abort_unless(
            Auth::check() && Auth::user()->isOperator(),
            403
        );

        return $next($request);
    }
}
