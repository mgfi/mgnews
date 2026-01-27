<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminUserController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'email'         => ['required', 'email', 'unique:users,email'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ]);

        $user = User::create([
            'name'           => 'Operator',
            'email'          => $data['email'],
            'password'       => Hash::make(Str::random(32)),
            'utype'          => 'USR',
            'permissions'    => $data['permissions'] ?? [],
            'invite_token'   => Str::uuid(),
            'invite_sent_at' => now(),
            'is_active'      => true,
        ]);

        // Mail::to($user->email)->send(
        //     new \App\Mail\OperatorInviteMail($user)
        // );
        Log::info('INVITE MAIL TEST', [
            'email' => $user->email,
            'token' => $user->invite_token,
        ]);

        \App\Services\AuditLogger::log(
            'create_operator',
            'User',
            [
                'email'       => $user->email,
                'permissions' => $user->permissions,
            ]
        );

        return redirect()
            ->route('admin.settings.index')
            ->with('success_operator_email', $user->email)
            ->with('redirect_after', route('admin.dashboard'));
    }
}
