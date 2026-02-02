@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">
        {{ __('admOpeInd.title') }}
    </h1>

    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>{{ __('admOpeInd.email') }}</th>
                <th>{{ __('admOpeInd.status') }}</th>
                <th>{{ __('admOpeInd.createdAt') }}</th>
                <th>{{ __('admOpeInd.createdBy') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($operators as $operator)
                <tr>
                    <td>{{ $operator->email }}</td>
                    <td>
                        @if ($operator->is_active)
                            <span class="badge bg-success">
                                {{ __('admOpeInd.active') }}
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                {{ __('admOpeInd.inactive') }}
                            </span>
                        @endif
                    </td>
                    <td>
                        {{ $operator->created_at?->format('Y-m-d') ?? '—' }}
                    </td>
                    <td>
                        {{ $operator->creator?->email ?? '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        {{ __('admOpeInd.empty') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
