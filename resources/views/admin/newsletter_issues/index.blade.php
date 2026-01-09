@extends('layouts.admin')

@section('content')

    <h1>Newslettery</h1>

    @if (session('success'))
        <div style="padding:10px;background:#d4edda;margin-bottom:10px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="padding:10px;background:#f8d7da;margin-bottom:10px;">
            {{ session('error') }}
        </div>
    @endif

    @if ($issues->count() === 0)
        <p>Brak newsletterów do wyświetlenia.</p>
    @else
        <table border="1" cellpadding="8">
            <tr>
                <th>ID</th>
                <th>Tytuł</th>
                <th>Temat</th>
                <th>Status</th>
                <th>Akcja</th>
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
                                <button type="submit">🚀 Wyślij</button>
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
