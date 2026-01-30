@extends('layouts.admin')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            @if (session('auto_redirect'))
                <div class="small text-muted mt-1">
                    {{ __('alerts.redirecting_dashboard') }}
                </div>
            @endif
        </div>
    @endif

    @if (session('auto_redirect'))
        <script>
            setTimeout(() => {
                window.location.href = "{{ session('auto_redirect') }}";
            }, 4000);
        </script>
    @endif

    <div class="container py-4">

        <h1 class="mb-4">⚙️ {{ __('admSetInd.title') }}</h1>

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
                                <label class="form-check-label">Dashboard</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="newsletter_view">
                                <label class="form-check-label">Newsletter – podgląd</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="newsletter_edit">
                                <label class="form-check-label">Newsletter – edycja</label>
                            </div>
                        </div>

                        <button class="btn btn-warning">
                            ➕ Dodaj operatora
                        </button>
                    </form>
                    <hr class="my-4">

                    <h5 class="mb-3">📋 Lista operatorów</h5>

                    @if ($operators->isEmpty())
                        <div class="text-muted">
                            Brak operatorów w systemie.
                        </div>
                    @else
                        <form method="POST" action="{{ route('admin.users.bulkUpdate') }}">
                            @csrf

                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead>
                                        <tr>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Uprawnienia</th>
                                            <th>Akcje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($operators as $operator)
                                            <tr>
                                                <td>
                                                    {{ $operator->email }}
                                                </td>

                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="users[{{ $operator->id }}][is_active]" value="1"
                                                            {{ $operator->is_active ? 'checked' : '' }}>
                                                    </div>
                                                </td>

                                                <td>
                                                    @php
                                                        $perms = $operator->permissions ?? [];
                                                    @endphp

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="users[{{ $operator->id }}][permissions][]"
                                                            value="view_dashboard"
                                                            {{ in_array('view_dashboard', $perms) ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            Dashboard
                                                        </label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="users[{{ $operator->id }}][permissions][]"
                                                            value="subscriber_view"
                                                            {{ in_array('subscriber_view', $perms) ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            Subskrybenci
                                                        </label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="users[{{ $operator->id }}][permissions][]"
                                                            value="newsletter_view"
                                                            {{ in_array('newsletter_view', $perms) ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            Newsletter – podgląd
                                                        </label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="users[{{ $operator->id }}][permissions][]"
                                                            value="newsletter_edit"
                                                            {{ in_array('newsletter_edit', $perms) ? 'checked' : '' }}>
                                                        <label class="form-check-label">
                                                            Newsletter – edycja
                                                        </label>
                                                    </div>
                                                </td>

                                                <td>
                                                    <a href="{{ route('admin.users.edit', $operator) }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        Edytuj
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button class="btn btn-primary mt-3">
                                💾 Zapisz zmiany
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    @endif

@endsection
