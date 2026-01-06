@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">

            <h1 class="mb-4 text-center">Logowanie</h1>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    Nieprawidłowy email lub hasło
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hasło</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Zaloguj się
                </button>
            </form>

        </div>
    </div>
@endsection
