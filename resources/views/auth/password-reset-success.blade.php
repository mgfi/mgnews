@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="alert alert-success text-center">
                    {{ __('auth.password_reset_success') }}
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        {{ __('auth.login') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
