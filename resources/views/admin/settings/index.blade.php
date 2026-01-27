@extends('layouts.admin')

@section('content')
    <div class="container py-4">

        <h1 class="mb-4">⚙️ {{ __('admSetInd.title') }}</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ __('admSetInd.success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <h4 class="mb-3">{{ __('admSetInd.cardTitle') }}</h4>

                <form method="POST" action="{{ route('admin.settings.save') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">{{ __('admSetInd.companyName') }}</label>
                        <input type="text" name="company_name" class="form-control" required
                            value="{{ old('company_name', $settings->company_name ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('admSetInd.companyAddress') }}</label>
                        <textarea name="company_address" class="form-control" rows="3">{{ old('company_address', $settings->company_address ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('admSetInd.companyEmail') }}</label>
                        <input type="email" name="company_email" class="form-control"
                            value="{{ old('company_email', $settings->company_email ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('admSetInd.privacyUrl') }}</label>
                        <input type="url" name="privacy_url" class="form-control"
                            placeholder="{{ __('admSetInd.privacyPlaceholder') }}"
                            value="{{ old('privacy_url', $settings->privacy_url ?? '') }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">{{ __('admSetInd.footerText') }}</label>
                        <textarea name="footer_text" class="form-control" rows="4">{{ old('footer_text', $settings->footer_text ?? '') }}</textarea>

                        <small class="text-muted">
                            {{ __('admSetInd.footerHint') }}
                        </small>
                    </div>

                    <button type="submit" class="btn btn-success">
                        💾 {{ __('admSetInd.save') }}
                    </button>

                </form>

            </div>
        </div>

    </div>
    @if (auth()->user()->utype === 'ADM')
        <div class="container py-4">
            <div class="card border-warning">
                <div class="card-body">

                    <h4 class="mb-3">👤 Operatorzy</h4>

                    <p class="text-muted">
                        Dodaj nowego operatora systemu. Operator przy pierwszym logowaniu
                        będzie musiał zmienić hasło.
                    </p>

                    @if (session('operator_password'))
                        <div class="alert alert-warning">
                            <strong>Operator utworzony.</strong><br>
                            Tymczasowe hasło:
                            <code>{{ session('operator_password') }}</code>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email operatora</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Uprawnienia</label>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_dashboard">
                                <label class="form-check-label">
                                    Dashboard
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="newsletter_view">
                                <label class="form-check-label">
                                    Newsletter – podgląd
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="newsletter_edit">
                                <label class="form-check-label">
                                    Newsletter – edycja
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-warning">
                            ➕ Dodaj operatora
                        </button>
                    </form>

                </div>
            </div>
        </div>
    @endif

@endsection
