<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\NewsletterSetting;

use App\Livewire\Admin\NewsletterIndex;
use App\Livewire\Admin\NewsletterEditor;

use App\Http\Controllers\NewsletterOpenController;
use App\Http\Controllers\NewsletterClickController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ChangePasswordController;

/*
|--------------------------------------------------------------------------
| ROOT – ENTRY POINT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| LOCALE SWITCH
|--------------------------------------------------------------------------
*/
Route::post('/locale/{locale}', function (string $locale, Request $request) {
    abort_unless(in_array($locale, ['pl', 'en']), 400);

    $request->session()->put('locale', $locale);
    app()->setLocale($locale);

    return redirect()->back();
})->name('locale.switch');

/*
|--------------------------------------------------------------------------
| AUTH – GUEST
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', fn() => view('auth.login'))->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            \App\Services\AuditLogger::log('login');

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ]);
    });

    // Password reset
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->middleware('throttle:5,10')
        ->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});
Route::get('/password/reset/success', fn() => view('auth.password-reset-success'))
    ->name('password.reset.success');

/*
|--------------------------------------------------------------------------
| AUTH – LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', function (Request $request) {

    \App\Services\AuditLogger::log('logout');

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| AUTH – AUTHENTICATED USER
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Force password change
    Route::get('/password/change', fn() => view('auth.password-change'))
        ->name('password.change');

    Route::post('/password/change', [ChangePasswordController::class, 'update'])
        ->name('password.update.force');

    /*
    |--------------------------------------------------------------------------
    | EMAIL VERIFICATION
    |--------------------------------------------------------------------------
    */

    // Notice
    Route::get('/email/verify', fn() => view('auth.verify-email'))
        ->name('verification.notice');

    // Verify link
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()
            ->route('install.demo')
            ->with('success', __('authVer.verified'));
    })
        ->middleware('signed')
        ->name('verification.verify');

    // Resend
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', __('authVer.sent'));
    })
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('admin.dashboard'))
            ->middleware('permission:view_dashboard')
            ->name('dashboard');

        Route::get('/subscribers', fn() => view('admin.subscribers.index'))
            ->middleware('permission:subscriber_view')
            ->name('subscribers.index');

        Route::get('/newsletters', NewsletterIndex::class)
            ->middleware('permission:newsletter_view')
            ->name('newsletters.index');

        Route::get('/newsletters/{newsletter}/edit-content', NewsletterEditor::class)
            ->middleware('permission:newsletter_edit')
            ->name('newsletters.edit');

        Route::get('/settings', function () {
            return view('admin.settings.index', [
                'settingsPl' => NewsletterSetting::firstOrCreate(
                    ['locale' => 'pl'],
                    ['company_name' => '']
                ),
                'settingsEn' => NewsletterSetting::firstOrCreate(
                    ['locale' => 'en'],
                    ['company_name' => '']
                ),
            ]);
        })
            ->middleware('permission:settings_view')
            ->name('settings.index');

        Route::post('/settings', function (Request $request) {
            $data = $request->validate([
                'pl.company_name'    => 'required|string|max:255',
                'pl.company_address' => 'nullable|string',
                'pl.company_email'   => 'nullable|email',
                'pl.privacy_url'     => 'nullable|url',
                'pl.footer_text'     => 'nullable|string',

                'en.company_name'    => 'required|string|max:255',
                'en.company_address' => 'nullable|string',
                'en.company_email'   => 'nullable|email',
                'en.privacy_url'     => 'nullable|url',
                'en.footer_text'     => 'nullable|string',
            ]);

            foreach (['pl', 'en'] as $locale) {
                NewsletterSetting::updateOrCreate(
                    ['locale' => $locale],
                    $data[$locale]
                );
            }

            return back()->with('success', 'Settings saved');
        })
            ->middleware('permission:settings_update')
            ->name('settings.save');

        /*
        |--------------------------------------------------------------------------
        | USERS (OPERATORS)
        |--------------------------------------------------------------------------
        */
        Route::middleware('admin')->group(function () {

            Route::post('/users', function (Request $request) {

                $data = $request->validate([
                    'email'        => ['required', 'email', 'unique:users,email'],
                    'permissions'  => ['nullable', 'array'],
                    'permissions.*' => ['string'],
                ]);

                $user = \App\Models\User::create([
                    'name'     => 'Operator',
                    'email'    => $data['email'],
                    'password' => \Illuminate\Support\Facades\Hash::make(
                        \Illuminate\Support\Str::random(32)
                    ),
                    'utype'    => 'USR',
                    'permissions' => $data['permissions'] ?? [],
                ]);

                \App\Services\AuditLogger::log(
                    'create_operator',
                    'User',
                    [
                        'email' => $user->email,
                        'permissions' => $user->permissions,
                    ]
                );

                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Operator created.');
            })->name('users.store');
            Route::get('/users/{user}/edit', function (\App\Models\User $user) {
                return view('admin.users.edit', compact('user'));
            })->name('users.edit');

            Route::put('/users/{user}', function (Request $request, \App\Models\User $user) {

                $data = $request->validate([
                    'permissions'   => ['nullable', 'array'],
                    'permissions.*' => ['string'],
                    'is_active'     => ['nullable', 'boolean'],
                ]);

                $user->update([
                    'permissions' => $data['permissions'] ?? [],
                    'is_active'   => $request->boolean('is_active'),
                ]);

                \App\Services\AuditLogger::log(
                    'update_operator',
                    'User',
                    [
                        'email' => $user->email,
                        'permissions' => $user->permissions,
                        'is_active' => $user->is_active,
                    ]
                );

                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Operator updated.');
            })->name('users.update');
        });
    });
Route::middleware(['auth', 'verified'])
    ->get('/operator/dashboard', function () {

        /** @var User $user */
        $user = Auth::user();

        abort_unless($user->isOperator(), 403);

        return view('operator.dashboard');
    })
    ->name('operator.dashboard');
/*
|--------------------------------------------------------------------------
| NEWSLETTER – PUBLIC
|--------------------------------------------------------------------------
*/
Route::prefix('newsletter')->as('newsletter.')->group(function () {

    Route::get('/unsubscribe/{token}', function (string $token) {
        $subscriber = Subscriber::where('unsubscribe_token', $token)->firstOrFail();
        return view('unsubscribe', compact('subscriber'));
    })->name('unsubscribe.form');

    Route::get('/open/{issue}/{subscriber?}', [NewsletterOpenController::class, 'open'])
        ->name('open');

    Route::get('/click/{hash}', [NewsletterClickController::class, 'click'])
        ->name('click');

    Route::post('/unsubscribe/{token}', function (string $token, Request $request) {
        $subscriber = Subscriber::where('unsubscribe_token', $token)->firstOrFail();

        $request->validate([
            'action' => ['required', 'in:unsubscribe,erase'],
        ]);

        if ($request->action === 'unsubscribe') {
            $subscriber->update([
                'is_active'       => false,
                'unsubscribed_at' => now(),
            ]);

            return view('unsubscribe-confirmation', [
                'message' => 'Zostałeś wypisany z newslettera.',
            ]);
        }

        DB::table('gdpr_erased_records')->insert([
            'email_hash' => hash('sha256', $subscriber->email),
            'erased_at'  => now(),
            'source'     => 'newsletter',
        ]);

        $subscriber->delete();

        return view('unsubscribe-confirmation', [
            'message' => 'Twoje dane zostały trwale usunięte z systemu.',
        ]);
    })->name('unsubscribe.process');
});

/*
|--------------------------------------------------------------------------
| PRIVACY POLICY
|--------------------------------------------------------------------------
*/
Route::get('/polityka-prywatnosci', fn() => view('privacy-policy'))
    ->name('privacy.policy');
Route::get('/install/admin', function () {
    return view('install.admin');
})->name('install.admin');
Route::post('/install/admin', function (Request $request) {

    $data = $request->validate([
        'email'    => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:8', 'confirmed'],
    ]);

    $user = \App\Models\User::create([
        'name'     => 'Administrator',
        'email'    => $data['email'],
        'password' => $data['password'],
        'role'     => 'ADMIN',
    ]);

    Auth::login($user);

    return redirect()->route('verification.notice');
})->name('install.admin.store');
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/install/demo', function () {
        return view('install.demo');
    })->name('install.demo');

    Route::post('/install/demo', function (Request $request) {

        if ($request->boolean('load_demo')) {
            \Illuminate\Support\Facades\Artisan::call('db:seed', [
                '--class' => 'DemoDataSeeder',
                '--force' => true,
            ]);
        }

        return redirect()->route('install.settings');
    })->name('install.demo.store');
});
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/install/settings', function () {
        return view('install.settings');
    })->name('install.settings');

    Route::post('/install/settings', function (Request $request) {

        $data = $request->validate([
            'company_name'  => ['required', 'string', 'max:255'],
            'system_email'  => ['required', 'email'],
            'default_locale' => ['required', 'in:pl,en'],
        ]);

        \App\Models\NewsletterSetting::updateOrCreate(
            ['locale' => $data['default_locale']],
            [
                'company_name'  => $data['company_name'],
                'company_email' => $data['system_email'],
            ]
        );

        cache()->put('app_settings_completed', true);

        return redirect()->route('install.finish');
    })->name('install.settings.store');
});
/*
|--------------------------------------------------------------------------
| INVITE ACCEPT (OPERATOR FIRST LOGIN)
|--------------------------------------------------------------------------
*/

Route::get('/invite/accept/{token}', function (string $token) {

    $user = \App\Models\User::where('invite_token', $token)->firstOrFail();

    return view('auth.invite-accept', [
        'token' => $token,
        'email' => $user->email,
    ]);
})->name('invite.accept');


Route::post('/invite/accept', function (Request $request) {

    $data = $request->validate([
        'token'    => ['required'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $user = \App\Models\User::where('invite_token', $data['token'])
        ->firstOrFail();

    $user->forceFill([
        'password'           => \Illuminate\Support\Facades\Hash::make($data['password']),
        'invite_token'       => null,
        'invite_sent_at'     => null,
        'email_verified_at'  => now(),
    ])->save();

    \App\Services\AuditLogger::log(
        'accept_invite',
        'User',
        ['email' => $user->email]
    );

    Auth::login($user);

    return redirect()
        ->route('admin.dashboard')
        ->with('success', 'Welcome!');
})->name('invite.accept.store');
