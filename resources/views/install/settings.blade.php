@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">

            <h1 class="mb-4 text-center">
                {{ __('settings.title') }}
            </h1>

            <form method="POST" action="{{ route('install.settings.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">
                        {{ __('settings.company_name') }}
                    </label>
                    <input type="text" name="company_name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        {{ __('settings.system_email') }}
                    </label>
                    <input type="email" name="system_email" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        {{ __('settings.default_locale') }}
                    </label>
                    <select name="default_locale" class="form-select">
                        <option value="pl">Polski</option>
                        <option value="en">English</option>
                    </select>
                </div>

                <button class="btn btn-primary w-100">
                    {{ __('settings.submit') }}
                </button>
            </form>

        </div>
    </div>
@endsection
