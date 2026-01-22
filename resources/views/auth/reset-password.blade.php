@extends('layouts.auth')

@section('content')
    @if ($errors->has('token'))
        <div class="alert alert-danger">
            {{ __('authRes.password_token_invalid') }}

        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email ?? '') }}" required autofocus>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password">{{ __('New password') }}</label>
            <input id="password" type="password" name="password" required>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation">{{ __('Confirm password') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <button type="submit">
            {{ __('Reset password') }}
        </button>
    </form>
@endsection
