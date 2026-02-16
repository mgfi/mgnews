@extends('layouts.panel')

@section('content')
    <div class="container-fluid">

        {{-- FILE: resources/views/admin/audit-logs/index.blade.php --}}
        <div class="alert alert-danger">
            TO JEST WIDOK: resources/views/admin/audit-logs/index.blade.php
        </div>

        @php
            $breadcrumbs = [
                [
                    'label' => __('breadcrumbs.dashboard'),
                    'route' => 'admin.dashboard',
                ],
                [
                    'label' => __('breadcrumbs.audit_logs'),
                ],
            ];
        @endphp

        @include('partials.breadcrumbs')

        <h1 class="h4 mb-4">
            {{ __('breadcrumbs.audit_logs') }}
        </h1>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>{{ __('admAudLog.when') }}</th>
                        <th>{{ __('admAudLog.user') }}</th>
                        <th>{{ __('admAudLog.action') }}</th>
                        <th>{{ __('admAudLog.subject') }}</th>
                        <th>{{ __('admAudLog.meta') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>

                            <td>
                                {{ $log->user?->email ?? __('admAudLog.system') }}
                            </td>

                            <td>
                                <code>{{ $log->action }}</code>
                            </td>

                            <td>{{ $log->subject ?? '—' }}</td>

                            <td>
                                @if ($log->meta)
                                    <pre class="mb-0 small">
{{ json_encode($log->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                                    </pre>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                {{ __('admAudLog.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                {{ __('common.pagination_info', [
                    'from' => $logs->firstItem(),
                    'to' => $logs->lastItem(),
                    'total' => $logs->total(),
                ]) }}
            </div>

            <div>
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
@endsection
