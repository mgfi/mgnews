@extends('layouts.panel')

@section('content')
    <div class="container-fluid">
        <div class="alert alert-danger">
            TO JEST DASHBOARD: resources/views/dashboard/admin.blade.php
        </div>
        <h1 class="h4 mb-4">
            Dashboard administratora
        </h1>

        {{-- KPI --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Subskrybenci</div>
                        <div class="fs-3 fw-bold">12 430</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Newslettery</div>
                        <div class="fs-3 fw-bold">86</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Open rate</div>
                        <div class="fs-3 fw-bold">41%</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">Click rate</div>
                        <div class="fs-3 fw-bold">9%</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- OSTATNIE KAMPANIE --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">
                Ostatnie kampanie
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tytuł</th>
                            <th>Status</th>
                            <th>Autor</th>
                            <th>Open</th>
                            <th>Click</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nowa kolekcja wiosna</td>
                            <td><span class="badge bg-success">Wysłany</span></td>
                            <td>operator@firma.pl</td>
                            <td>42%</td>
                            <td>10%</td>
                            <td>2026-02-05</td>
                        </tr>
                        <tr>
                            <td>Promocja weekendowa</td>
                            <td><span class="badge bg-secondary">Draft</span></td>
                            <td>admin@firma.pl</td>
                            <td>—</td>
                            <td>—</td>
                            <td>2026-02-07</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- OPERATORZY --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">
                Operatorzy
            </div>

            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Ostatnia aktywność</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>operator1@firma.pl</td>
                            <td><span class="badge bg-success">Aktywny</span></td>
                            <td>2026-02-06</td>
                        </tr>
                        <tr>
                            <td>operator2@firma.pl</td>
                            <td><span class="badge bg-warning text-dark">Nieaktywny</span></td>
                            <td>—</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="row g-3">

            <div class="col-md-3">
                <a href="{{ route('admin.newsletters.index') }}" class="card text-decoration-none shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">Newslettery</div>
                        <div class="text-muted small">Zarządzaj kampaniami</div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.subscribers.index') }}" class="card text-decoration-none shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">Subskrybenci</div>
                        <div class="text-muted small">Lista i statusy</div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.operators.index') }}" class="card text-decoration-none shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">Operatorzy</div>
                        <div class="text-muted small">Zespół</div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.settings.index') }}" class="card text-decoration-none shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">Ustawienia</div>
                        <div class="text-muted small">Konfiguracja</div>
                    </div>
                </a>
            </div>

        </div>

    </div>
@endsection
