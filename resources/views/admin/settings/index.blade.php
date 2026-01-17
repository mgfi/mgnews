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
@endsection
