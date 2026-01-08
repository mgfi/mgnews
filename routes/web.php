<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Subscriber;
use App\Models\NewsletterIssue;
use App\Livewire\Admin\NewsletterIndex;
use App\Livewire\Admin\NewsletterEditor;
/*
|--------------------------------------------------------------------------
| AUTH – WŁASNY (BEZ BREEZE / VOLT)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Nieprawidłowy email lub hasło.',
        ]);
    });

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', function (Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'utype' => 'USR',
        ]);

        return redirect()->route('login');
    });
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->middleware('auth')->name('logout');



/*
|--------------------------------------------------------------------------
| STRONY PUBLICZNE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
});



/*
|--------------------------------------------------------------------------
| PANEL UŻYTKOWNIKA
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/profile', 'profile')->name('profile');
});



/*
|--------------------------------------------------------------------------
| PANEL ADMINA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::get('/subscribers', function () {
            return view('admin.subscribers.index');
        })->name('admin.subscribers.index');

        // LISTA NEWSLETTERÓW
        Route::get('/newsletters', NewsletterIndex::class)
            ->name('admin.newsletters.index');

        // EDYCJA NEWSLETTERA (LIVEWIRE)
        Route::get('/newsletters/{newsletter}/edit-content', NewsletterEditor::class)
            ->name('admin.newsletters.edit');

        // send → później
    });



/*
|--------------------------------------------------------------------------
| NEWSLETTER – UNSUBSCRIBE / RODO
|--------------------------------------------------------------------------
*/

Route::get('/unsubscribe/{token}', function (string $token) {

    $subscriber = Subscriber::where('unsubscribe_token', $token)->firstOrFail();

    return view('unsubscribe', compact('subscriber'));
})->name('unsubscribe.form');


Route::post('/unsubscribe/{token}', function (string $token, Request $request) {

    $subscriber = Subscriber::where('unsubscribe_token', $token)->firstOrFail();

    $request->validate([
        'action' => ['required', 'in:unsubscribe,erase'],
    ]);

    /*
     |--------------------------------------------------------------
     | ART. 7 ust. 3 RODO — COFNIĘCIE ZGODY
     |--------------------------------------------------------------
     */
    if ($request->action === 'unsubscribe') {

        $subscriber->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);

        return view('unsubscribe-confirmation', [
            'message' => 'Zostałeś wypisany z newslettera.',
        ]);
    }

    /*
     |--------------------------------------------------------------
     | ART. 17 RODO — PRAWO DO BYCIA ZAPOMNIANYM
     |--------------------------------------------------------------
     */
    if ($request->action === 'erase') {

        DB::table('gdpr_erased_records')->insert([
            'email_hash' => hash('sha256', $subscriber->email),
            'erased_at' => now(),
            'source' => 'newsletter',
        ]);

        $subscriber->delete();

        return view('unsubscribe-confirmation', [
            'message' => 'Twoje dane zostały trwale usunięte z systemu.',
        ]);
    }
})->name('unsubscribe.process');
