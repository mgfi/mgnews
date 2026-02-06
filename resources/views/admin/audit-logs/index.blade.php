@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">
        {{ __('admAudLog.title') }}
    </h1>

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
                    <td>
                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                    </td>

                    <td>
                        {{ $log->user?->email ?? __('admAudLog.system') }}
                    </td>

                    <td>
                        <code>{{ $log->action }}</code>
                    </td>

                    <td>
                        {{ $log->subject ?? '—' }}
                    </td>

                    <td>
                        @if ($log->meta)
                            <pre class="mb-0 small">{{ json_encode($log->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
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

    {{-- PAGINATION --}}
    <div class="mt-3" style="display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">

        {{-- LEFT --}}
        <div class="text-muted small">
            {{ __('common.pagination_info', [
                'from' => $logs->firstItem(),
                'to' => $logs->lastItem(),
                'total' => $logs->total(),
            ]) }}
        </div>

        {{-- CENTER --}}
        <div class="text-center">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>

        {{-- RIGHT (EMPTY, BALANCE) --}}
        <div></div>

    </div>
@endsection
