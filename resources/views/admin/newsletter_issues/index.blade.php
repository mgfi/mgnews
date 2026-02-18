@extends('layouts.panel')

@section('content')
    <div class="container-fluid">

        {{-- FILE: resources/views/admin/newsletter_issues/index.blade.php --}}
        <div class="alert alert-danger">
            TO JEST WIDOK: resources/views/admin/newsletter_issues/index.blade.php
        </div>

        @php
            $breadcrumbs = [
                [
                    'label' => __('breadcrumbs.newsletter_issues'),
                ],
            ];
        @endphp

        @include('partials.breadcrumbs')

        <h1 class="h4 mb-4">
            {{ __('breadcrumbs.newsletter_issues') }}
        </h1>

        {{-- FLASH --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ __('admNewIssInd.success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ __('admNewIssInd.error') }}
            </div>
        @endif

        @if ($issues->count() === 0)
            <p class="text-muted">
                {{ __('admNewIssInd.empty') }}
            </p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>{{ __('breadcrumbs.campaign') }}</th>
                            <th>{{ __('admNewIssInd.table.title') }}</th>
                            <th>{{ __('admNewIssInd.table.status') }}</th>
                            <th>Open </th>
                            <th>Click </th>
                            <th>CTR</th>
                            <th>Data wysyłki</th>
                            <th class="text-end">{{ __('admNewIssInd.table.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($issues as $issue)
                            <tr>

                                {{-- ID --}}
                                <td>
                                    <span class="fw-semibold">#{{ $issue->id }}</span>
                                </td>

                                {{-- CAMPAIGN --}}
                                <td>
                                    @if ($issue->campaign)
                                        <a href="{{ route('admin.campaigns.show', $issue->campaign) }}"
                                            class="text-decoration-none fw-semibold">
                                            {{ $issue->campaign->title }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- TITLE --}}
                                <td>
                                    {{ $issue->title }}
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @if ($issue->status === 'draft')
                                        <span class="badge bg-secondary">
                                            DRAFT
                                        </span>
                                    @elseif ($issue->status === 'sending')
                                        <span class="badge bg-warning text-dark">
                                            SENDING
                                        </span>
                                    @elseif ($issue->status === 'sent')
                                        <span class="badge bg-success">
                                            SENT
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark">
                                            {{ strtoupper($issue->status) }}
                                        </span>
                                    @endif
                                </td>

                                {{-- OPEN RATE --}}
                                <td>
                                    @if ($issue->isSent())
                                        <span class="fw-semibold">
                                            {{ $issue->uniqueOpens() }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- CLICK RATE --}}
                                <td>
                                    @if ($issue->isSent())
                                        <span class="fw-semibold">
                                            {{ $issue->uniqueClicks() }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                {{-- CTR --}}
                                <td>{{ number_format($issue->ctr(), 1) }}%</td>
                                {{-- CTR --}}

                                {{-- SENT DATE --}}
                                <td>
                                    {{ $issue->sent_at?->format('Y-m-d H:i') ?? '—' }}
                                </td>

                                {{-- ACTION --}}
                                <td class="text-end">

                                    @if ($issue->status === 'draft')
                                        <form method="POST" action="{{ route('admin.newsletter-issues.send', $issue) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                🚀 {{ __('admNewIssInd.send') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">
                                            —
                                        </span>
                                    @endif

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-3 d-flex justify-content-center">
                    {{ $issues->links() }}
                </div>
            </div>
        @endif

    </div>
@endsection
