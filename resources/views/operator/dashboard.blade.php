@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">
        {{ __('opDash.title') }}
    </h1>

    {{-- KPI --}}
    <div class="row g-3 mb-4">

        @if (auth()->user()->hasPermission('subscriber_view'))
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">
                            {{ __('opDash.subscribers_count') }}
                        </div>
                        <div class="fs-3 fw-bold">
                            {{ \App\Models\Subscriber::count() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->hasPermission('newsletter_view'))
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">
                            {{ __('opDash.newsletters_count') }}
                        </div>
                        <div class="fs-3 fw-bold">
                            {{ \App\Models\NewsletterIssue::count() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- RECENT SUBSCRIBERS --}}
    @if (auth()->user()->hasPermission('subscriber_view'))
        <div class="card shadow-sm mb-4">
            <div class="card-header fw-bold">
                {{ __('opDash.recent_subscribers') }}
            </div>

            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('opDash.email') }}</th>
                            <th>{{ __('opDash.date') }}</th>
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
                                    {{ __('opDash.no_data') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- QUICK ACTIONS --}}
    <div class="card shadow-sm">
        <div class="card-header fw-bold">
            {{ __('opDash.quick_actions') }}
        </div>

        <div class="card-body d-flex gap-2">
            @if (auth()->user()->hasPermission('subscriber_view'))
                <a href="{{ route('admin.subscribers.index') }}" class="btn btn-primary">
                    {{ __('opDash.view_subscribers') }}
                </a>
            @endif

            @if (auth()->user()->hasPermission('newsletter_view'))
                <a href="{{ route('admin.newsletters.index') }}" class="btn btn-outline-primary">
                    {{ __('opDash.view_newsletters') }}
                </a>
            @endif
        </div>
    </div>
@endsection
