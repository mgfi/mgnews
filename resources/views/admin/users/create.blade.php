@extends('layouts.panel')

@section('content')
    <div class="container-fluid">

        {{-- FILE: resources/views/admin/users/create.blade.php --}}
        <div class="alert alert-danger">
            TO JEST WIDOK: resources/views/admin/users/create.blade.php
        </div>

        @php
            $breadcrumbs = [
                [
                    'label' => __('breadcrumbs.operators'),
                    'route' => 'admin.operators.index',
                ],
                [
                    'label' => __('common.create'),
                ],
            ];
        @endphp

        @include('partials.breadcrumbs')

        <h1 class="h4 mb-4">
            {{ __('admUseCre.title') }}
        </h1>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            {{-- EMAIL --}}
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">
                    {{ __('admUseCre.email') }}
                </label>
                <input id="email" type="email" name="email" class="form-control" required>
            </div>

            {{-- PERMISSIONS --}}
            <fieldset class="mb-4">
                <legend class="fw-semibold mb-2">
                    {{ __('admUseCre.permissions') }}
                </legend>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="view_dashboard"
                        id="perm_view_dashboard">
                    <label class="form-check-label" for="perm_view_dashboard">
                        {{ __('admUseCre.perm_dashboard') }}
                    </label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="newsletter_view"
                        id="perm_newsletter_view">
                    <label class="form-check-label" for="perm_newsletter_view">
                        {{ __('admUseCre.perm_newsletter_view') }}
                    </label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="newsletter_edit"
                        id="perm_newsletter_edit">
                    <label class="form-check-label" for="perm_newsletter_edit">
                        {{ __('admUseCre.perm_newsletter_edit') }}
                    </label>
                </div>
            </fieldset>

            {{-- ACTIONS --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    {{ __('admUseCre.submit') }}
                </button>

                <a href="{{ route('admin.operators.index') }}" class="btn btn-secondary">
                    {{ __('common.cancel') }}
                </a>
            </div>

    </div>
@endsection
