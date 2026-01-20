@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">

        <h1 class="mb-4 text-center">
            Accept invitation
        </h1>

        <p class="text-center mb-4">
            You are setting a password for account:<br>
            <strong>{{ $email }}</strong>
        </p>

        <form method="POST" action="{{ route('invite.accept.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Confirm password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100">
                Set password and login
            </button>
        </form>

    </div>
</div>
@endsection
