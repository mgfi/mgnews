<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnsureNotInstalled
{
    public function handle(Request $request, Closure $next)
    {
        $installed = DB::table('app_state')
            ->where('key', 'installed')
            ->value('value');

        if ($installed === 'true') {
            abort(403);
        }

        return $next($request);
    }
}
