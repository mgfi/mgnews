<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {

                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                Log::info('Password reset', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'ip'      => $request->ip(),
                ]);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()
            ->route('login')
            ->with('success', __('passwords.reset'))
            : back()->withErrors(['email' => __($status)]);
    }
}
