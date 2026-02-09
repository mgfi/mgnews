@extends('layouts.panel')

@section('content')

    <h1>{{ __('admNewIssInd.title') }}</h1>

    @if (session('success'))
        <div style="padding:10px;background:#d4edda;margin-bottom:10px;">
            {{ __('admNewIssInd.success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="padding:10px;background:#f8d7da;margin-bottom:10px;">
            {{ __('admNewIssInd.error') }}
        </div>
    @endif

    @if ($issues->count() === 0)
        <p>{{ __('admNewIssInd.empty') }}</p>
    @else
        <table border="1" cellpadding="8">
            <tr>
                <th>{{ __('admNewIssInd.table.id') }}</th>
                <th>{{ __('admNewIssInd.table.title') }}</th>
                <th>{{ __('admNewIssInd.table.subject') }}</th>
                <th>{{ __('admNewIssInd.table.status') }}</th>
                <th>{{ __('admNewIssInd.table.action') }}</th>
            </tr>

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
                                <button type="submit">🚀 {{ __('admNewIssInd.send') }}</button>
                            </form>
                        @else
                            {{ strtoupper($issue->status) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

@endsection
