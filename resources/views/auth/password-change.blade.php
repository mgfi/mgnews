@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h1 class="h4 mb-2 text-center">
                        {{ __('authPasCha.title') }}
                    </h1>

                    <p class="text-muted text-center mb-4">
                        {{ __('authPasCha.subtitle') }}
                    </p>

                    {{-- @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ __('authPasCha.error') }}
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif --}}

                    <form method="POST" action="{{ route('password.update.force') }}">
                        @csrf

                        {{-- CURRENT PASSWORD --}}
                        <div class="mb-3">
                            <label class="form-label">
                                {{ __('authPasCha.current') }}
                            </label>
                            <input type="password" name="current_password" class="form-control" required autofocus>
                            @error('current_password')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- NEW PASSWORD --}}
                        <div class="mb-3">
                            <label class="form-label">
                                {{ __('authPasCha.new') }}
                            </label>

                            <div class="password-wrapper">
                                <input type="password" name="password" id="password" class="form-control" required>

                                <button type="button" class="password-toggle" data-target="password"
                                    aria-label="Toggle password visibility">
                                    @include('icons.eye-off')
                                </button>
                            </div>
                        </div>

                        {{-- CONFIRM --}}
                        <div class="mb-4">
                            <label class="form-label">
                                {{ __('authPasCha.confirm') }}
                            </label>

                            <div class="password-wrapper">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" required>

                                <button type="button" class="password-toggle" data-target="password_confirmation"
                                    aria-label="Toggle password visibility">
                                    @include('icons.eye-off')
                                </button>
                            </div>
                        </div>

                        {{-- PASSWORD RULES --}}
                        <ul class="list-unstyled small mb-4" id="password-rules">
                            <li id="rule-length" class="text-danger">❌ {{ __('authPasCha.rule_length') }}</li>
                            <li id="rule-number" class="text-danger">❌ {{ __('authPasCha.rule_number') }}</li>
                            <li id="rule-special" class="text-danger">❌ {{ __('authPasCha.rule_special') }}</li>
                            <li id="rule-uppercase" class="text-danger">❌ {{ __('authPasCha.rule_uppercase') }}</li>
                            <li id="rule-blacklist" class="text-danger">❌ {{ __('authPasCha.rule_blacklist') }}</li>
                        </ul>

                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('authPasCha.submit') }}
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

    {{-- przekazanie SVG do JS --}}
    <script>
        window.eyeOn = @json(view('icons.eye')->render());
        window.eyeOff = @json(view('icons.eye-off')->render());
    </script>
@endsection
