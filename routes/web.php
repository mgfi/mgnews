<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
| EMAIL VERIFICATION
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
/*
|--------------------------------------------------------------------------
| ROOT – ENTRY POINT
|--------------------------------------------------------------------------
| Jeden punkt wejścia, brak pętli redirectów
*/

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
});
Route::post('/locale/{locale}', function (string $locale, Request $request) {

    if (!in_array($locale, ['pl', 'en'])) {
        abort(400);
    }

    // zapis do sesji
    $request->session()->put('locale', $locale);

    // ustawienie runtime
    app()->setLocale($locale);

    return redirect()->back();
})->name('locale.switch');



Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| AUTH – LOGOWANIE (BEZ REJESTRACJI)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {

        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Nieprawidłowy email lub hasło.',
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| AUTH – WYLOGOWANIE
|--------------------------------------------------------------------------
*/
Route::post('/logout', function (Request $request) {

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');
Route::middleware('auth')->group(function () {

    Route::get('/password/change', function () {
        return view('auth.password-change');
    })->name('password.change');

    Route::post('/password/change', [ChangePasswordController::class, 'update'])
        ->name('password.update.force');
});
/*
|--------------------------------------------------------------------------
| PANEL ADMINA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        /*
        |-------------------------
        | DASHBOARD
        |-------------------------
        */
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        /*
        |-------------------------
        | SUBSCRIBERS
        |-------------------------
        */
        Route::get('/subscribers', function () {
            return view('admin.subscribers.index');
        })->name('subscribers.index');

        /*
        |-------------------------
        | NEWSLETTERS
        |-------------------------
        */
        Route::get('/newsletters', NewsletterIndex::class)
            ->name('newsletters.index');

        Route::get('/newsletters/{newsletter}/edit-content', NewsletterEditor::class)
            ->name('newsletters.edit');

        /*
        |-------------------------
        | SETTINGS (PL / EN)
        |-------------------------
        */
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
        })->name('settings.index');

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
        })->name('settings.save');
    });

/*
|--------------------------------------------------------------------------
| NEWSLETTER – PUBLIC (OPEN / CLICK / UNSUBSCRIBE)
|--------------------------------------------------------------------------
| Publiczne, bez logowania
*/
Route::prefix('newsletter')
    ->as('newsletter.')
    ->group(function () {

        /*
        |-------------------------
        | UNSUBSCRIBE FORM
        |-------------------------
        */
        Route::get('/unsubscribe/{token}', function (string $token) {

            $subscriber = Subscriber::where('unsubscribe_token', $token)
                ->firstOrFail();

            return view('unsubscribe', compact('subscriber'));
        })->name('unsubscribe.form');

        /*
        |-------------------------
        | OPEN TRACKING
        |-------------------------
        */
        Route::get('/open/{issue}/{subscriber?}', [NewsletterOpenController::class, 'open'])
            ->name('open');

        /*
        |-------------------------
        | CLICK TRACKING
        |-------------------------
        */
        Route::get('/click/{hash}', [NewsletterClickController::class, 'click'])
            ->name('click');

        /*
        |-------------------------
        | UNSUBSCRIBE / ERASE (POST)
        |-------------------------
        */
        Route::post('/unsubscribe/{token}', function (string $token, Request $request) {

            $subscriber = Subscriber::where('unsubscribe_token', $token)
                ->firstOrFail();

            $request->validate([
                'action' => ['required', 'in:unsubscribe,erase'],
            ]);

            // ART. 7 ust. 3 RODO — COFNIĘCIE ZGODY
            if ($request->action === 'unsubscribe') {

                $subscriber->update([
                    'is_active'       => false,
                    'unsubscribed_at' => now(),
                ]);

                return view('unsubscribe-confirmation', [
                    'message' => 'Zostałeś wypisany z newslettera.',
                ]);
            }

            // ART. 17 RODO — PRAWO DO BYCIA ZAPOMNIANYM
            if ($request->action === 'erase') {

                DB::table('gdpr_erased_records')->insert([
                    'email_hash' => hash('sha256', $subscriber->email),
                    'erased_at'  => now(),
                    'source'     => 'newsletter',
                ]);

                $subscriber->delete();

                return view('unsubscribe-confirmation', [
                    'message' => 'Twoje dane zostały trwale usunięte z systemu.',
                ]);
            }
        })->name('unsubscribe.process');
    });
Route::middleware('auth')->group(function () {

    // Screen: "Verify your email"
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Verify link from email
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', __('Email verified successfully.'));
    })->middleware(['signed'])->name('verification.verify');

    // Resend verification email
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', __('Verification link sent.'));
    })->middleware(['throttle:6,1'])->name('verification.send');
});
/*
|--------------------------------------------------------------------------
| POLITYKA PRYWATNOŚCI
|--------------------------------------------------------------------------
*/
Route::get('/polityka-prywatnosci', function () {
    return view('privacy-policy');
})->name('privacy.policy');
