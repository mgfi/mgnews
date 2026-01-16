@extends('layouts.admin')

@section('content')
    <div class="container py-4">

        <h1 class="mb-4">⚙️ Settings</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <h4 class="mb-3">Newsletter settings</h4>

                <form method="POST" action="{{ route('admin.settings.save') }}">
                    @csrf

                    {{-- Company name --}}
                    <div class="mb-3">
                        <label class="form-label">Company name</label>
                        <input type="text" name="company_name" class="form-control" required
                            value="{{ old('company_name', $settings->company_name ?? '') }}">
                    </div>

                    {{-- Company address --}}
                    <div class="mb-3">
                        <label class="form-label">Company address</label>
                        <textarea name="company_address" class="form-control" rows="3">{{ old('company_address', $settings->company_address ?? '') }}</textarea>
                    </div>

                    {{-- Company email --}}
                    <div class="mb-3">
                        <label class="form-label">Company email</label>
                        <input type="email" name="company_email" class="form-control"
                            value="{{ old('company_email', $settings->company_email ?? '') }}">
                    </div>

                    {{-- Privacy policy URL --}}
                    <div class="mb-3">
                        <label class="form-label">Privacy policy URL</label>
                        <input type="url" name="privacy_url" class="form-control"
                            placeholder="https://example.com/privacy-policy"
                            value="{{ old('privacy_url', $settings->privacy_url ?? '') }}">
                    </div>

                    {{-- Footer text --}}
                    <div class="mb-4">
                        <label class="form-label">Footer text</label>
                        <textarea name="footer_text" class="form-control" rows="4">{{ old('footer_text', $settings->footer_text ?? '') }}</textarea>

                        <small class="text-muted">
                            This content will be appended to every newsletter email.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-success">
                        💾 Save settings
                    </button>

                </form>

            </div>
        </div>

    </div>
@endsection
