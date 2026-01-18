@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4 text-center">

            <h1 class="mb-3">
                Verify your email
            </h1>

            <p class="mb-4">
                Please verify your email address by clicking the link we sent to you.
            </p>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="btn btn-primary w-100">
                    Resend verification email
                </button>
            </form>

        </div>
    </div>
@endsection
