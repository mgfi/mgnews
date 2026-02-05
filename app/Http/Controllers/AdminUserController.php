<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\AuditLogger;

class AdminUserController extends Controller
{
    /**
     * Create new operator (admin action).
     * Operator is forced to change password on first login.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email'         => ['required', 'email', 'unique:users,email'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);

        // Generate strong temporary password (never revealed)
        $temporaryPassword = Str::random(32);

        $operator = User::create([
            'name'                 => 'Operator',
            'email'                => $data['email'],
            'password'             => Hash::make($temporaryPassword),
            'utype'                => User::TYPE_USER,
            'permissions'          => $data['permissions'] ?? [],
            'invite_token'         => Str::uuid(),
            'invite_sent_at'       => now(),
            'is_active'            => true,
            'must_change_password' => true,          // 🔐 FORCE PASSWORD CHANGE
            'created_by'           => auth()->id(),   // 🔍 AUDIT / OWNERSHIP
        ]);

        /*
         |--------------------------------------------------------------------------
         | Invite mail (disabled in dev)
         |--------------------------------------------------------------------------
         |
         | Mail::to($operator->email)->send(
         |     new \App\Mail\OperatorInviteMail($operator)
         | );
         |
         */

        // Dev / test log instead of mail
        Log::info('OPERATOR INVITE GENERATED', [
            'email' => $operator->email,
            'token' => $operator->invite_token,
        ]);

        // Audit log
        AuditLogger::log(
            'operator.created',
            'User',
            [
                'email'       => $operator->email,
                'permissions' => $operator->permissions,
            ]
        );

        return redirect()
            ->route('admin.settings.index')
            ->with('success_operator_email', $operator->email)
            ->with('redirect_after', route('admin.dashboard'));
    }
}
