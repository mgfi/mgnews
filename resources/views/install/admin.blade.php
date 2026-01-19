@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">

            <h1 class="mb-4 text-center">
                {{ __('install.title') }}
            </h1>

            <form method="POST" action="{{ route('install.admin.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">{{ __('install.email') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('install.password') }}</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">{{ __('install.password_confirm') }}</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button class="btn btn-primary w-100">
                    {{ __('install.submit') }}
                </button>
            </form>

        </div>
    </div>
@endsection
