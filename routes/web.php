<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Subscriber;
use App\Livewire\Admin\NewsletterIndex;
use App\Livewire\Admin\NewsletterEditor;

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


/*
|--------------------------------------------------------------------------
| PANEL ADMINA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/subscribers', function () {
            return view('admin.subscribers.index');
        })->name('subscribers.index');

        // LISTA NEWSLETTERÓW
        Route::get('/newsletters', NewsletterIndex::class)
            ->name('newsletters.index');

        // EDYCJA TREŚCI NEWSLETTERA
        Route::get('/newsletters/{newsletter}/edit-content', NewsletterEditor::class)
            ->name('newsletters.edit');
    });


/*
|--------------------------------------------------------------------------
| NEWSLETTER – UNSUBSCRIBE / RODO (PUBLIC)
|--------------------------------------------------------------------------
| Publiczne, bez logowania
*/
Route::prefix('newsletter')
    ->as('newsletter.')
    ->group(function () {

        /*
         |------------------------------------------
         | FORMULARZ WYBORU AKCJI
         |------------------------------------------
         */
        Route::get('/unsubscribe/{token}', function (string $token) {

            $subscriber = Subscriber::where('unsubscribe_token', $token)->firstOrFail();

            return view('unsubscribe', compact('subscriber'));
        })->name('unsubscribe.form');


        /*
         |------------------------------------------
         | WYKONANIE AKCJI (POST)
         |------------------------------------------
         */
        Route::post('/unsubscribe/{token}', function (string $token, Request $request) {

            $subscriber = Subscriber::where('unsubscribe_token', $token)->firstOrFail();

            $request->validate([
                'action' => ['required', 'in:unsubscribe,erase'],
            ]);

            /*
             |------------------------------------------
             | ART. 7 ust. 3 RODO — COFNIĘCIE ZGODY
             |------------------------------------------
             */
            if ($request->action === 'unsubscribe') {

                $subscriber->update([
                    'is_active'       => false,
                    'unsubscribed_at' => now(),
                ]);

                return view('unsubscribe-confirmation', [
                    'message' => 'Zostałeś wypisany z newslettera.',
                ]);
            }

            /*
             |------------------------------------------
             | ART. 17 RODO — PRAWO DO BYCIA ZAPOMNIANYM
             |------------------------------------------
             */
            if ($request->action === 'erase') {

                DB::table('gdpr_erased_records')->insert([
                    'email_hash' => hash('sha256', $subscriber->email),
                    'erased_at'  => now(),
                    'source'     => 'newsletter',
                ]);

                // soft delete (RODO)
                $subscriber->delete();

                return view('unsubscribe-confirmation', [
                    'message' => 'Twoje dane zostały trwale usunięte z systemu.',
                ]);
            }
        })->name('unsubscribe.process');
    });
Route::get('/polityka-prywatnosci', function () {
    return view('privacy-policy');
})->name('privacy.policy');
// use App\Http\Controllers\UnsubscribeController;

// Route::get('/unsubscribe/{token}', [UnsubscribeController::class, 'unsubscribe'])
//     ->name('unsubscribe');
