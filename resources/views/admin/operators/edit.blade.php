@extends('layouts.panel')

@section('content')
    <div class="container-fluid">

        {{-- FILE: resources/views/admin/operators/edit.blade.php --}}
        <div class="alert alert-danger">
            TO JEST WIDOK: resources/views/admin/operators/edit.blade.php
        </div>

        @php
            $breadcrumbs = [
                [
                    'label' => __('breadcrumbs.operators'),
                    'route' => 'admin.operators.index',
                ],
                [
                    'label' => $operator->email,
                ],
            ];
        @endphp

        @include('partials.breadcrumbs')

        <h1 class="h4 mb-4">
            {{ __('breadcrumbs.operators') }}: {{ $operator->email }}
        </h1>

        <form method="POST" action="{{ route('admin.operators.update', $operator) }}">
            @csrf
            @method('PUT')

            {{-- EMAIL --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    {{ __('admOpeEdit.email') }}
                </label>
                <input type="text" class="form-control" value="{{ $operator->email }}" disabled>
            </div>

            {{-- PERMISSIONS --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    {{ __('admOpeEdit.permissions') }}
                </label>

                @foreach ($allPermissions as $permission)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission }}"
                            id="perm_{{ $permission }}" @checked(in_array($permission, $operator->permissions ?? [])) @disabled(!$operator->is_active)>
                        <label class="form-check-label fw-semibold" for="perm_{{ $permission }}">
                            {{ __('permissions.' . $permission) }}
                        </label>
                    </div>
                @endforeach
            </div>

            {{-- ACTIONS --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    {{ __('admOpeEdit.save') }}
                </button>

                <a href="{{ route('admin.operators.index') }}" class="btn btn-secondary">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>

    </div>
@endsection
