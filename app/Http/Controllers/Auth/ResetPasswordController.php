<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, string $token)
    {
        if (! $token) {
            return redirect()
                ->route('password.request')
                ->withErrors(['email' => __('auth.invalid_token')]);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {

                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => null,
                ])->save();

                AuditLogger::log('password_reset', 'User', [
                    'email' => $user->email,
                ]);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('password.reset.success')
            : back()->withErrors(['email' => __($status)]);
    }
}
