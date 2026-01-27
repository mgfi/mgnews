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
use App\Http\Controllers\AdminUserController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| LOCALE
|--------------------------------------------------------------------------
*/
Route::post('/locale/{locale}', function (string $locale, Request $request) {
    abort_unless(in_array($locale, ['pl', 'en']), 400);
    $request->session()->put('locale', $locale);
    app()->setLocale($locale);
    return back();
})->name('locale.switch');

/*
|--------------------------------------------------------------------------
| AUTH – GUEST
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', fn() => view('auth.login'))->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            // BLOCK INACTIVE USERS
            if (!Auth::user()->is_active) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                session()->flash(
                    'error',
                    __('alerts.account_inactive')
                );

                return back();
            }

            $request->session()->regenerate();

            \App\Services\AuditLogger::log('login');

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ]);
    });


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
| LOGOUT
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
| AUTH – VERIFIED
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/password/change', fn() => view('auth.password-change'))
        ->name('password.change');

    Route::post('/password/change', [ChangePasswordController::class, 'update'])
        ->name('password.update.force');

    Route::get('/email/verify', fn() => view('auth.verify-email'))
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('install.demo');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back();
    })->middleware('throttle:6,1')->name('verification.send');
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

        Route::get('/settings', fn() => view('admin.settings.index'))
            ->middleware('permission:settings_view')
            ->name('settings.index');

        Route::post('/settings', function (Request $request) {
            $data = $request->validate([
                'pl.company_name' => 'required|string|max:255',
                'en.company_name' => 'required|string|max:255',
            ]);

            foreach (['pl', 'en'] as $locale) {
                NewsletterSetting::updateOrCreate(
                    ['locale' => $locale],
                    $data[$locale]
                );
            }

            return back()->with('success', 'Settings saved');
        })->middleware('permission:settings_update')->name('settings.save');

        /*
        |--------------------------------------------------------------------------
        | USERS (OPERATORS)
        |--------------------------------------------------------------------------
        */
        Route::middleware('admin')->group(function () {

            Route::get('/users/create', fn() => view('admin.users.create'))
                ->name('users.create');

            Route::post('/users', [AdminUserController::class, 'store'])
                ->name('users.store');

            Route::get(
                '/users/{user}/edit',
                fn(User $user) =>
                view('admin.users.edit', compact('user'))
            )->name('users.edit');

            Route::put('/users/{user}', function (Request $request, User $user) {

                $data = $request->validate([
                    'permissions' => ['nullable', 'array'],
                    'permissions.*' => ['string'],
                    'is_active' => ['nullable', 'boolean'],
                ]);

                $user->update([
                    'permissions' => $data['permissions'] ?? [],
                    'is_active' => $request->boolean('is_active'),
                ]);

                \App\Services\AuditLogger::log('update_operator', 'User', [
                    'email' => $user->email,
                ]);

                return redirect()->route('admin.dashboard');
            })->name('users.update');
        });
    });

/*
|--------------------------------------------------------------------------
| OPERATOR DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])
    ->get('/operator/dashboard', function () {
        abort_unless(Auth::user()->isOperator(), 403);
        return view('operator.dashboard');
    })
    ->name('operator.dashboard');
// Route::middleware(['auth', 'verified'])
//     ->get('/operator/dashboard', function () {
//         $user = Auth::user();

//         dd(
//             get_class($user),
//             method_exists($user, 'isOperator'),
//             get_class_methods($user)
//         );
//     });
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

        if ($request->action === 'unsubscribe') {
            $subscriber->update([
                'is_active' => false,
                'unsubscribed_at' => now(),
            ]);
            return view('unsubscribe-confirmation');
        }

        DB::table('gdpr_erased_records')->insert([
            'email_hash' => hash('sha256', $subscriber->email),
            'erased_at' => now(),
            'source' => 'newsletter',
        ]);

        $subscriber->delete();

        return view('unsubscribe-confirmation');
    })->name('unsubscribe.process');
});

/*
|--------------------------------------------------------------------------
| INVITE ACCEPT
|--------------------------------------------------------------------------
*/
Route::get('/invite/accept/{token}', function (string $token) {
    $user = User::where('invite_token', $token)->firstOrFail();
    return view('auth.invite-accept', compact('token'));
})->name('invite.accept');

Route::post('/invite/accept', function (Request $request) {

    $data = $request->validate([
        'token' => ['required'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $user = User::where('invite_token', $data['token'])->firstOrFail();

    $user->forceFill([
        'password' => bcrypt($data['password']),
        'invite_token' => null,
        'email_verified_at' => now(),
    ])->save();

    \App\Services\AuditLogger::log('accept_invite', 'User', ['email' => $user->email]);

    Auth::login($user);

    return redirect()->route('admin.dashboard');
})->name('invite.accept.store');
