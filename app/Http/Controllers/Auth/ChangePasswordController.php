<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[0-9]/',                                        // cyfra
                'regex:/[A-Z]/',                                        // duża litera
                'regex:/[!@#$%^&*()_+\-=\[\]{};:\'"\\\\|,.<>\/?~]/',    // znak specjalny

                function ($attribute, $value, $fail) {
                    if (strtolower($value) === 'admin1234') {
                        $fail('This password is not allowed.');
                    }
                },
            ],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        Auth::logoutOtherDevices($request->password);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', __('Password changed successfully.'));
    }
}
