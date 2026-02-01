<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OperatorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || ! $user->isOperator()) {
            abort(403);
        }

        return $next($request);
    }
}
