<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    public function update(Request $request)
    {
        // =========================
        // VALIDATION
        // =========================
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[0-9]/',                                     // digit
                'regex:/[A-Z]/',                                     // uppercase
                'regex:/[!@#$%^&*()_+\-=\[\]{};:\'"\\\\|,.<>\/?~]/', // special char
                function ($attribute, $value, $fail) {
                    if (strtolower($value) === 'admin1234') {
                        $fail(__('This password is not allowed.'));
                    }
                },
            ],
        ]);

        // =========================
        // UPDATE PASSWORD
        // =========================
        $user = $request->user();

        $user->update([
            'password'             => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        // =========================
        // LOGOUT + SESSION RESET
        // =========================
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // =========================
        // REDIRECT TO LOGIN
        // =========================
        return redirect()
            ->route('login')
            ->with('password_changed', true);
    }
}
