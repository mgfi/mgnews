<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log(
        string $action,
        ?string $subject = null,
        array $meta = []
    ): void {
        $request = app(Request::class);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'subject'    => $subject,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'meta'       => $meta,
        ]);
    }
}
