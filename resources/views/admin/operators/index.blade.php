@extends('layouts.panel')

@section('content')
    <div class="container-fluid">

        {{-- FILE: resources/views/admin/operators/index.blade.php --}}
        <div class="alert alert-danger">
            TO JEST WIDOK: resources/views/admin/operators/index.blade.php
        </div>



        <h1 class="h4 mb-4">
            {{ __('breadcrumbs.operators') }}
        </h1>

        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>{{ __('admOpeInd.email') }}</th>
                    <th>{{ __('admOpeInd.status') }}</th>
                    <th>{{ __('admOpeInd.createdAt') }}</th>
                    <th>{{ __('admOpeInd.createdBy') }}</th>
                    <th class="text-end">{{ __('admOpeInd.actions') }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($operators as $operator)
                    <tr
                        @unless ($operator->trashed())
                            onclick="window.location='{{ route('admin.operators.edit', $operator) }}'"
                            style="cursor: pointer;"
                        @endunless>
                        <td>{{ $operator->email }}</td>

                        <td>
                            @if ($operator->trashed())
                                <span class="badge bg-danger">
                                    {{ __('admOpeInd.deleted') }}
                                </span>
                            @elseif (!$operator->is_active)
                                <span class="badge bg-secondary">
                                    {{ __('admOpeInd.inactive') }}
                                </span>
                            @else
                                <span class="badge bg-success">
                                    {{ __('admOpeInd.active') }}
                                </span>
                            @endif
                        </td>

                        <td>
                            {{ $operator->created_at?->format('Y-m-d') ?? '—' }}
                        </td>

                        <td>
                            {{ $operator->creator?->email ?? '—' }}
                        </td>

                        <td class="text-end" onclick="event.stopPropagation()">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    {{ __('admOpeInd.actions') }}
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    @unless ($operator->trashed())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.operators.edit', $operator) }}">
                                                {{ __('admOpeInd.edit') }}
                                            </a>
                                        </li>
                                    @endunless

                                    @if ($operator->trashed())
                                        <li>
                                            <form method="POST"
                                                action="{{ route('admin.operators.restore', $operator->id) }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-success">
                                                    {{ __('admOpeInd.restore') }}
                                                </button>
                                            </form>
                                        </li>
                                    @else
                                        <li>
                                            <form method="POST" action="{{ route('admin.operators.delete', $operator) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    {{ __('admOpeInd.delete') }}
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            {{ __('admOpeInd.empty') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
@endsection
