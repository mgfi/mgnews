@extends('layouts.panel')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="alert alert-danger">
            TO JEST DASHBOARD: resources\views\dashboard\operator.blade.php
        </div>
        @php
            $breadcrumbs = [
                [
                    'label' => __('breadcrumbs.dashboard'),
                ],
            ];
        @endphp

        @include('partials.breadcrumbs')


        <h1 class="h4 mb-4">
            Dashboard operatora
        </h1>

        {{-- KPI --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Subskrybenci</div>
                        <div class="fs-3 fw-bold">
                            {{ $stats['subscribers'] ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Open rate</div>
                        <div class="fs-3 fw-bold">
                            {{ $stats['open_rate'] ?? '—' }}%
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Click rate</div>
                        <div class="fs-3 fw-bold">
                            {{ $stats['click_rate'] ?? '—' }}%
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Ostatnia wysyłka</div>
                        <div class="fw-semibold">
                            {{ $stats['last_send'] ?? 'Brak' }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- OSTATNIE KAMPANIE --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                Ostatnie newslettery
            </div>

            <div class="table-responsive">
                <table class="table mb-0 table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tytuł</th>
                            <th>Status</th>
                            <th>Open</th>
                            <th>Click</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($newsletters ?? [] as $newsletter)
                            <tr>
                                <td>{{ $newsletter->subject }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $newsletter->status }}
                                    </span>
                                </td>
                                <td>{{ $newsletter->open_rate }}%</td>
                                <td>{{ $newsletter->click_rate }}%</td>
                                <td>{{ $newsletter->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Brak newsletterów
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SZYBKIE AKCJE --}}
        <div class="row g-3">

            <div class="col-md-4">
                <a href="{{ route('operator.newsletters.index') }}" class="card text-decoration-none shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">
                            Newslettery
                        </div>
                        <div class="text-muted small">
                            Twórz i edytuj kampanie
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('operator.subscribers.index') }}" class="card text-decoration-none shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">
                            Subskrybenci
                        </div>
                        <div class="text-muted small">
                            Lista i ręczne wypisanie
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">
                            Status systemu
                        </div>
                        <div class="text-muted small">
                            Wysyłki działają poprawnie
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
