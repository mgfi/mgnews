@extends('layouts.panel')

@section('content')
    <div class="container-fluid">

        {{-- FILE: resources/views/admin/settings/index.blade.php --}}
        <div class="alert alert-danger">
            TO JEST WIDOK: resources/views/admin/settings/index.blade.php
        </div>



        <h1 class="h4 mb-4">
            {{ __('breadcrumbs.settings') }}
        </h1>

        {{-- ===== FLASH SUCCESS ===== --}}
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

        {{-- ===== AUTO REDIRECT ===== --}}
        @if (session('auto_redirect'))
            <script>
                setTimeout(() => {
                    window.location.href = "{{ session('auto_redirect') }}";
                }, 4000);
            </script>
        @endif

        {{-- ================= SETTINGS ================= --}}
        <div class="container py-4 px-0">

            <div class="card">
                <div class="card-body">

                    <h4 class="mb-3">
                        {{ __('admSetInd.cardTitle') }}
                    </h4>

                    <form method="POST" action="{{ route('admin.settings.save') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">
                                {{ __('admSetInd.companyName') }}
                            </label>
                            <input type="text" name="company_name" class="form-control" required
                                value="{{ old('company_name', $settings->company_name ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                {{ __('admSetInd.companyAddress') }}
                            </label>
                            <textarea name="company_address" class="form-control" rows="3">{{ old('company_address', $settings->company_address ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                {{ __('admSetInd.companyEmail') }}
                            </label>
                            <input type="email" name="company_email" class="form-control"
                                value="{{ old('company_email', $settings->company_email ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                {{ __('admSetInd.privacyUrl') }}
                            </label>
                            <input type="url" name="privacy_url" class="form-control"
                                placeholder="{{ __('admSetInd.privacyPlaceholder') }}"
                                value="{{ old('privacy_url', $settings->privacy_url ?? '') }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                {{ __('admSetInd.footerText') }}
                            </label>
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

        {{-- ================= AUDIT LOG ================= --}}
        @if (auth()->user()->utype === 'ADM')
            <div class="container py-4 px-0">
                <div class="card border-secondary">
                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <h4 class="mb-1">
                                🧾 {{ __('admSetInd.audit_logs') }}
                            </h4>
                            <p class="text-muted mb-0">
                                {{ __('admSetInd.audit_logs_desc') }}
                            </p>
                        </div>

                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-secondary">
                            {{ __('admSetInd.audit_logs_open') }}
                        </a>

                    </div>
                </div>
            </div>
        @endif

        {{-- ================= OPERATORS ================= --}}
        @if (auth()->user()->utype === 'ADM')
            <div class="container py-4 px-0">
                <div class="card border-warning">
                    <div class="card-body">

                        <h4 class="mb-3">
                            👤 {{ __('breadcrumbs.operators') }}
                        </h4>

                        <p class="text-muted">
                            {{ __('admSetInd.operators_desc') ?? ' ' }}
                        </p>

                        <form method="POST" action="{{ route('admin.operators.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">
                                    {{ __('admSetInd.operator_email') ?? 'Email' }}
                                </label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    {{ __('admSetInd.permissions') ?? 'Uprawnienia' }}
                                </label>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                        value="view_dashboard">
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
                                ➕ {{ __('admSetInd.add_operator') ?? 'Dodaj operatora' }}
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
