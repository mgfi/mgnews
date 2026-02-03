@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">
        {{ __('admOpeEdit.title') }}
    </h1>

    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.operators.update', $operator) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">
                        {{ __('admOpeEdit.email') }}
                    </label>

                    <input type="text" class="form-control" value="{{ $operator->email }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        {{ __('admOpeEdit.permissions') }}
                    </label>

                    <div class="d-flex flex-column gap-2">
                        @foreach ($allPermissions as $permission)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="{{ $permission }}" id="perm_{{ $permission }}"
                                    {{ in_array($permission, $operator->permissions ?? []) ? 'checked' : '' }}>

                                <label class="form-check-label" for="perm_{{ $permission }}">
                                    {{ __('permissions.' . $permission) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">
                        {{ __('common.save') }}
                    </button>

                    <a href="{{ route('admin.operators.index') }}" class="btn btn-secondary">
                        {{ __('common.back') }}
                    </a>
                </div>

            </form>

        </div>
    </div>
@endsection
