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
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('admNewIssInd.table.id') }}</th>
                            <th>{{ __('admNewIssInd.table.title') }}</th>
                            <th>{{ __('admNewIssInd.table.subject') }}</th>
                            <th>{{ __('admNewIssInd.table.status') }}</th>
                            <th>{{ __('admNewIssInd.table.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($issues as $issue)
                            <tr>
                                <td>{{ $issue->id }}</td>
                                <td>{{ $issue->title }}</td>
                                <td>{{ $issue->subject }}</td>
                                <td>{{ $issue->status }}</td>
                                <td>
                                    @if ($issue->status === 'draft')
                                        <form method="POST" action="{{ route('admin.newsletter-issues.send', $issue) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                🚀 {{ __('admNewIssInd.send') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">
                                            {{ strtoupper($issue->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
@endsection
