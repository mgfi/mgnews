@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4 text-center">

            <h1 class="mb-3">
                {{ __('authVer.title') }}
            </h1>

            <p class="mb-4">
                {{ __('authVer.description') }}
            </p>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="btn btn-primary w-100">
                    {{ __('authVer.resend') }}
                </button>
            </form>

        </div>
    </div>
@endsection
