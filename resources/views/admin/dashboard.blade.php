@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">
        {{ __('admDash.title') }}
    </h1>

    {{-- KPI --}}
    <div class="row g-3 mb-4">

        {{-- SUBSCRIBERS --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">
                        {{ __('admDash.subscribers_count') }}
                    </div>
                    <div class="fs-3 fw-bold">
                        {{ \App\Models\Subscriber::count() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- USERS --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">
                        {{ __('admDash.users_count') }}
                    </div>
                    <div class="fs-3 fw-bold">
                        {{ \App\Models\User::count() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- ADMINS --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">
                        {{ __('admDash.admins_count') }}
                    </div>
                    <div class="fs-3 fw-bold">
                        {{ \App\Models\User::where('utype', 'ADM')->count() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- RECENT SUBSCRIBERS --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">
            {{ __('admDash.recent_subscribers') }}
        </div>

        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>{{ __('admDash.email') }}</th>
                        <th>{{ __('admDash.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (\App\Models\Subscriber::latest()->limit(5)->get() as $sub)
                        <tr>
                            <td>{{ $sub->email }}</td>
                            <td>{{ $sub->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">
                                {{ __('admDash.no_data') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="card shadow-sm">
        <div class="card-header fw-bold">
            {{ __('admDash.quick_actions') }}
        </div>

        <div class="card-body d-flex gap-2">
            <a href="{{ route('admin.subscribers.index') }}" class="btn btn-primary">
                {{ __('admDash.manage_subscribers') }}
            </a>
        </div>
    </div>
@endsection
