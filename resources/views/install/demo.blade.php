@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4 text-center">

            <h1 class="mb-3">
                Load demo data
            </h1>

            <p class="mb-4">
                You can load sample data to explore the application.
            </p>

            <form method="POST" action="{{ route('install.demo.store') }}">
                @csrf

                <div class="form-check mb-4 text-start">
                    <input class="form-check-input" type="checkbox" name="load_demo" id="load_demo">
                    <label class="form-check-label" for="load_demo">
                        Load demo data
                    </label>
                </div>

                <button class="btn btn-primary w-100">
                    Continue
                </button>
            </form>

        </div>
    </div>
@endsection
