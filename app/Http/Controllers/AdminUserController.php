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
            'must_change_password' => true,
            'created_by'           => auth()->id(),
        ]);

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
            ->with('success', __('alerts.operator_created'))
            ->with('redirect_after', route('admin.dashboard'));
    }

    /**
     * Bulk update operators (status + permissions)
     */
    public function bulkUpdate(Request $request)
    {
        $users = $request->input('users', []);

        foreach ($users as $userId => $data) {
            $user = User::find($userId);

            if (! $user || ! $user->isOperator()) {
                continue;
            }

            // Active / inactive
            $user->is_active = isset($data['is_active']);

            // Permissions
            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $user->permissions = $data['permissions'];
            }

            $user->save();

            AuditLogger::log(
                'operator.bulk_updated',
                'User',
                ['email' => $user->email]
            );
        }

        return back()->with('success', __('alerts.operator_permissions_updated'));
    }
}
