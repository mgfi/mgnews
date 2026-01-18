@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">

            <h1 class="mb-3 text-center">
                {{ __('authForgot.title') }}
            </h1>

            <p class="text-muted text-center mb-4">
                {{ __('authForgot.description') }}
            </p>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ __('authForgot.success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">
                        {{ __('authForgot.email') }}
                    </label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    {{ __('authForgot.submit') }}
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}">
                    {{ __('authForgot.back_to_login') }}
                </a>
            </div>

        </div>
    </div>
@endsection
