@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">

            <h1 class="mb-4">Gotowe</h1>

            <div class="alert alert-success">
                {{ $message }}
            </div>

            <p class="text-muted">
                Dziękujemy.
            </p>

        </div>
    </div>
@endsection
