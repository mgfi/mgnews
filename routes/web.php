<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Services\AuditLogger;
use App\Models\AuditLog;
use App\Models\NewsletterClick;
use App\Models\NewsletterIssue;
use App\Models\NewsletterOpen;
use App\Models\NewsletterSetting;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\Campaign;

use App\Livewire\Admin\NewsletterIndex;
use App\Livewire\Admin\NewsletterEditor;
use App\Livewire\Operator\NewsletterIndex as OperatorNewsletterIndex;
use App\Livewire\Operator\NewsletterEditor as OperatorNewsletterEditor;
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

    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isOperator()) {
        return redirect()->route('operator.dashboard');
    }

    abort(403);
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

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        // BLOCK INACTIVE OR DELETED USERS
        if (! $user->is_active || $user->deleted_at !== null) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            session()->flash('error', __('alerts.account_inactive'));

            return back();
        }

        $request->session()->regenerate();

        AuditLogger::log('login');

        if ($user->must_change_password) {
            return redirect()->route('password.change');
        }

        return redirect('/');
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

    AuditLogger::log('logout');

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
Route::middleware(['auth', 'verified', 'admin', 'panel.ui'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {

            $subscribersCount      = Subscriber::where('is_active', 1)->count();
            $subscribersAllCount   = Subscriber::count();

            $newslettersDraftCount = NewsletterIssue::whereNull('sent_at')->count();
            $newslettersSentCount  = NewsletterIssue::whereNotNull('sent_at')->count();

            // === OPEN / CLICK RATE ===

            $sentCount = max($newslettersSentCount, 1);

            $opensCount  = NewsletterOpen::count();
            $clicksCount = NewsletterClick::count();

            $openRate = round(($opensCount / $sentCount) * 100, 1);
            $clickRate = round(($clicksCount / $sentCount) * 100, 1);

            return view('dashboard.admin', [
                'navbar'                    => 'partials.navbar-admin',
                'sidebar'                   => 'partials.sidebar-admin',

                'subscribersCount'          => $subscribersCount,
                'subscribersAllCount'       => $subscribersAllCount,
                'newslettersDraftCount'     => $newslettersDraftCount,
                'newslettersSentCount'      => $newslettersSentCount,

                'openRate'                  => $openRate,
                'clickRate'                 => $clickRate,
            ]);
        })->name('dashboard');
        Route::get('/campaigns', function () {

            $campaigns = Campaign::withCount('newsletters')
                ->orderByDesc('created_at')
                ->get();

            return view('admin.campaigns.index', [
                'campaigns' => $campaigns,
                'navbar'  => 'partials.navbar-admin',
                'sidebar' => 'partials.sidebar-admin',
            ]);
        })->name('campaigns.index');

        Route::get('/campaigns/{campaign}', function (Campaign $campaign) {

            $newsletters = NewsletterIssue::query()
                ->where('campaign_id', $campaign->id)
                ->orderByDesc('created_at')
                ->get();

            return view('admin.campaigns.show', [
                'campaign'    => $campaign,
                'newsletters' => $newsletters,
                'navbar'      => 'partials.navbar-admin',
                'sidebar'     => 'partials.sidebar-admin',
            ]);
        })->name('campaigns.show')
            ->middleware('permission:newsletter_view');
        /*
|--------------------------------------------------------------------------
| OPERATORS – LIST
|--------------------------------------------------------------------------
*/
        Route::get('/operators', function (Request $request) {

            $query = User::withTrashed()
                ->where('utype', User::TYPE_USER)
                ->with('creator')
                ->orderBy('created_at', 'desc');

            match ($request->get('status')) {
                'deleted'  => $query->onlyTrashed(),
                'active'   => $query->where('is_active', true),
                'inactive' => $query->where('is_active', false),
                default    => null,
            };

            $operators = $query->get();

            return view('admin.operators.index', [
                'operators' => $operators,
                'navbar'    => 'partials.navbar-admin',
                'sidebar'   => 'partials.sidebar-admin',
            ]);
        })
            ->middleware('permission:operator_view')
            ->name('operators.index');

        Route::post('/operators', [AdminUserController::class, 'store'])
            ->middleware('permission:operator_create')
            ->name('operators.store');
        Route::post('/operators/bulk-update', [AdminUserController::class, 'bulkUpdate'])
            ->middleware('permission:operator_update')
            ->name('operators.bulkUpdate');
        /*
        |--------------------------------------------------------------------------
        | OPERATORS – EDIT (READ ONLY – STEP 1.4.1)
        |--------------------------------------------------------------------------
        */
        Route::get('/operators/{user}/edit', function (User $user) {

            abort_unless($user->isOperator(), 404);

            $allPermissions = [
                'newsletter_view',
                'newsletter_edit',
                'subscriber_view',
                'stats_view',
            ];

            return view('admin.operators.edit', [
                'operator'       => $user,
                'allPermissions' => $allPermissions,
            ]);
        })->middleware('permission:operator_update')
            ->name('operators.edit');
        /*
|--------------------------------------------------------------------------
| OPERATORS – UPDATE PERMISSIONS (STEP 1.4.2)
|--------------------------------------------------------------------------
*/
        Route::put('/operators/{user}', function (Request $request, User $user) {

            abort_unless($user->isOperator(), 404);

            $data = $request->validate([
                'permissions'   => ['nullable', 'array'],
                'permissions.*' => ['string'],
            ]);

            $user->update([
                'permissions' => $data['permissions'] ?? [],
            ]);

            AuditLogger::log('update_operator_permissions', 'User', [
                'email' => $user->email,
            ]);

            return back()->with('success', __('alerts.operator_permissions_updated'));
        })->middleware('permission:operator_update')
            ->name('operators.update');

        /*
        |--------------------------------------------------------------------------
        | OPERATORS – TOGGLE ACTIVE / INACTIVE
        |--------------------------------------------------------------------------
        */
        Route::patch('/operators/{user}/toggle', function (User $user) {

            abort_unless($user->isOperator(), 404);

            $user->update([
                'is_active' => ! $user->is_active,
            ]);

            AuditLogger::log(
                $user->is_active ? 'activate_operator' : 'deactivate_operator',
                'User',
                ['email' => $user->email]
            );

            return back()->with('success', __('alerts.operator_status_changed'));
        })->middleware('permission:operator_update')
            ->name('operators.toggle');

        /*
        |--------------------------------------------------------------------------
        | OPERATORS – SOFT DELETE
        |--------------------------------------------------------------------------
        */
        Route::delete('/operators/{user}', function (User $user) {

            abort_unless($user->isOperator(), 404);

            $user->delete();

            AuditLogger::log('delete_operator', 'User', [
                'email' => $user->email,
            ]);

            return back()->with('success', __('alerts.operator_deleted'));
        })->middleware('permission:operator_delete')
            ->name('operators.delete');
        /*
|--------------------------------------------------------------------------
| OPERATORS – RESTORE (STEP 2.1)
|--------------------------------------------------------------------------
*/
        Route::post('/operators/{user}/restore', function (int $user) {

            $user = User::withTrashed()->findOrFail($user);

            abort_unless($user->isOperator(), 404);
            abort_unless($user->trashed(), 404);

            $user->restore();

            AuditLogger::log('operator.restored', 'User', [
                'email' => $user->email,
            ]);

            return back()->with('success', __('alerts.operator_restored'));
        })->middleware('permission:operator_update')
            ->name('operators.restore');

        /*
|--------------------------------------------------------------------------
| SUBSCRIBERS
|--------------------------------------------------------------------------
*/
        Route::get('/subscribers', fn() => view('admin.subscribers.index', [
            'navbar'  => 'partials.navbar-admin',
            'sidebar' => 'partials.sidebar-admin',
        ]))
            ->middleware('permission:subscriber_view')
            ->name('subscribers.index');

        /*
        |--------------------------------------------------------------------------
        | NEWSLETTERS
        |--------------------------------------------------------------------------
        */
        Route::get('/newsletters', NewsletterIndex::class)
            ->middleware('permission:newsletter_view')
            ->name('newsletters.index');

        Route::get('/newsletters/{newsletter}/edit-content', NewsletterEditor::class)
            ->middleware('permission:newsletter_edit')
            ->name('newsletters.edit');

        /*
        |--------------------------------------------------------------------------
        | SETTINGS
        |--------------------------------------------------------------------------
        */
        Route::get('/settings', function () {

            $operators = User::query()
                ->where('utype', User::TYPE_USER)
                ->orderBy('email')
                ->get();

            return view('admin.settings.index', [
                'operators' => $operators,
                'navbar'    => 'partials.navbar-admin',
                'sidebar'   => 'partials.sidebar-admin',
            ]);
        })
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
        })->middleware('permission:settings_update')
            ->name('settings.save');



        Route::get('/audit-logs', function () {

            $logs = AuditLog::with('user')
                ->latest()
                ->paginate(30);

            return view('admin.audit-logs.index', compact('logs'));
        })->middleware('permission:view_audit_logs')
            ->name('audit-logs.index');
    });

/*
|--------------------------------------------------------------------------
| OPERATOR PANEL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'operator', 'panel.ui'])
    ->prefix('operator')
    ->as('operator.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('operator.dashboard', [
            'navbar'  => 'partials.navbar-operator',
            'sidebar' => 'partials.sidebar-operator',
        ]))
            ->name('dashboard');

        Route::get('/subscribers', fn() => view('operator.subscribers.index', [
            'navbar'  => 'partials.navbar-operator',
            'sidebar' => 'partials.sidebar-operator',
        ]))
            ->middleware('permission:subscriber_view')
            ->name('subscribers.index');

        Route::get('/newsletters', OperatorNewsletterIndex::class)
            ->middleware('permission:newsletter_view')
            ->name('newsletters.index');
        Route::get('/newsletters/{newsletterIssue}/edit', OperatorNewsletterEditor::class)
            ->middleware('permission:newsletter_edit')
            ->name('newsletters.edit');
    });
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
                'is_active'        => false,
                'unsubscribed_at'  => now(),
            ]);

            return view('unsubscribe-confirmation');
        }

        DB::table('gdpr_erased_records')->insert([
            'email_hash' => hash('sha256', $subscriber->email),
            'erased_at'  => now(),
            'source'     => 'newsletter',
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
        'token'    => ['required'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    $user = User::where('invite_token', $data['token'])->firstOrFail();

    $user->forceFill([
        'password'           => bcrypt($data['password']),
        'invite_token'       => null,
        'email_verified_at'  => now(),
    ])->save();

    AuditLogger::log('accept_invite', 'User', [
        'email' => $user->email,
    ]);

    Auth::login($user);

    return redirect('/');
})->name('invite.accept.store');
