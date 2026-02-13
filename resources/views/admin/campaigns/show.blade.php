@extends('layouts.panel')

@section('content')
    <div class="alert alert-danger">
        TO JEST Widok Kampani: resources\views\admin\campaigns\show.blade.php
    </div>
    <div class="container-fluid">

        {{-- BREADCRUMBS --}}
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    Kampanie
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $campaign->title }}
                </li>
            </ol>
        </nav>

        <h1 class="h4 mb-4">
            Kampania: {{ $campaign->title }}
        </h1>

        {{-- STATUS --}}
        <div class="mb-3">
            @if ($campaign->is_active)
                <span class="badge bg-success">Aktywna</span>
            @else
                <span class="badge bg-secondary">Nieaktywna</span>
            @endif
        </div>

        {{-- LISTA NEWSLETTERÓW --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-semibold">
                Newslettery w kampanii
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tytuł</th>
                            <th>Status</th>
                            <th>Autor</th>
                            <th>Otwarcia</th>
                            <th>Kliknięcia</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($newsletters as $newsletter)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.newsletters.edit', $newsletter) }}"
                                        class="fw-semibold text-decoration-none">
                                        {{ $newsletter->title_pl }}
                                    </a>
                                </td>

                                <td>
                                    @if ($newsletter->status === 'sent')
                                        <span class="badge bg-success">Wysłany</span>
                                    @elseif ($newsletter->status === 'sending')
                                        <span class="badge bg-warning text-dark">Wysyłany</span>
                                    @else
                                        <span class="badge bg-secondary">Szkic</span>
                                    @endif
                                </td>

                                <td>
                                    {{ optional($newsletter->creator)->email ?? '—' }}
                                </td>

                                <td>{{ $newsletter->uniqueOpens() }}</td>
                                <td>{{ $newsletter->uniqueClicks() }}</td>

                                <td>
                                    {{ optional($newsletter->sent_at)->format('Y-m-d') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Brak newsletterów w tej kampanii
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
