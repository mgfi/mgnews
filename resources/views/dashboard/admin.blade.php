@extends('layouts.panel')

@section('content')
    <div class="container-fluid">

        <div class="alert alert-danger">
            TO JEST DASHBOARD: resources/views/dashboard/admin.blade.php
        </div>

        <h1 class="h4 mb-4">
            {{ __('dashAdm.title') }}
        </h1>

        {{-- KPI --}}
        <div class="row g-4 mb-4">

            {{-- Subskrybenci aktywni --}}
            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">
                            {{ __('dashAdm.kpi.subscribers_active') }}
                        </div>
                        <div class="fs-3 fw-bold">
                            {{ number_format($subscribersCount) }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Subskrybenci wszyscy --}}
            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">
                            {{ __('dashAdm.kpi.subscribers_all') }}
                        </div>
                        <div class="fs-3 fw-bold">
                            {{ number_format($subscribersAllCount) }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Newslettery – szkice --}}
            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">
                            {{ __('dashAdm.kpi.newsletters_draft') }}
                        </div>
                        <div class="fs-3 fw-bold">
                            {{ number_format($newslettersDraftCount) }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Newslettery – wysłane --}}
            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">
                            {{ __('dashAdm.kpi.newsletters_sent') }}
                        </div>
                        <div class="fs-3 fw-bold">
                            {{ number_format($newslettersSentCount) }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Wskaźnik otwarć --}}
            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">
                            {{ __('dashAdm.kpi.open_rate') }}
                        </div>
                        <div class="fs-3 fw-bold">
                            {{ $openRate }}%
                        </div>
                    </div>
                </div>
            </div>

            {{-- Wskaźnik kliknięć --}}
            <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="text-muted small">
                            {{ __('dashAdm.kpi.click_rate') }}
                        </div>
                        <div class="fs-3 fw-bold">
                            {{ $clickRate }}%
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- KAMPANIE --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">
                {{ __('dashAdm.sections.campaigns') }}
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('dashAdm.table.topic') }}</th>
                            <th>{{ __('dashAdm.table.active') }}</th>
                            <th>{{ __('dashAdm.table.last_sent') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <a href="{{ route('admin.campaigns.show', 1) }}" class="fw-semibold text-decoration-none">
                                    Trendy Wiosna 2026
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    {{ __('dashAdm.common.yes') }}
                                </span>
                            </td>
                            <td>2026-02-05</td>
                        </tr>

                        <tr>
                            <td>
                                <a href="#" class="fw-semibold text-decoration-none">
                                    Black Friday 2025
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ __('dashAdm.common.no') }}
                                </span>
                            </td>
                            <td>2025-11-29</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- OSTATNIE NEWSLETTERY --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">
                {{ __('dashAdm.sections.recent_newsletters') }}
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('dashAdm.table.title') }}</th>
                            <th>{{ __('dashAdm.table.campaign') }}</th>
                            <th>{{ __('dashAdm.table.status') }}</th>
                            <th>{{ __('dashAdm.table.author') }}</th>
                            <th>{{ __('dashAdm.table.open') }}</th>
                            <th>{{ __('dashAdm.table.click') }}</th>
                            <th>{{ __('dashAdm.table.date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nowa kolekcja wiosna</td>
                            <td>Trendy Wiosna 2026</td>
                            <td><span class="badge bg-success">{{ __('dashAdm.status.sent') }}</span></td>
                            <td>operator@firma.pl</td>
                            <td>42%</td>
                            <td>10%</td>
                            <td>2026-02-05</td>
                        </tr>

                        <tr>
                            <td>Promocja weekendowa</td>
                            <td>—</td>
                            <td><span class="badge bg-secondary">{{ __('dashAdm.status.draft') }}</span></td>
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
                {{ __('dashAdm.sections.operators') }}
            </div>

            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('dashAdm.table.email') }}</th>
                            <th>{{ __('dashAdm.table.status') }}</th>
                            <th>{{ __('dashAdm.table.last_activity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>operator1@firma.pl</td>
                            <td><span class="badge bg-success">{{ __('dashAdm.status.active') }}</span></td>
                            <td>2026-02-06</td>
                        </tr>
                        <tr>
                            <td>operator2@firma.pl</td>
                            <td><span class="badge bg-warning text-dark">{{ __('dashAdm.status.inactive') }}</span></td>
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
                        <div class="fw-semibold mb-1">
                            {{ __('dashAdm.quick.newsletters_title') }}
                        </div>
                        <div class="text-muted small">
                            {{ __('dashAdm.quick.newsletters_desc') }}
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.subscribers.index') }}" class="card text-decoration-none shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">
                            {{ __('dashAdm.quick.subscribers_title') }}
                        </div>
                        <div class="text-muted small">
                            {{ __('dashAdm.quick.subscribers_desc') }}
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.operators.index') }}" class="card text-decoration-none shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">
                            {{ __('dashAdm.quick.operators_title') }}
                        </div>
                        <div class="text-muted small">
                            {{ __('dashAdm.quick.operators_desc') }}
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('admin.settings.index') }}" class="card text-decoration-none shadow-sm h-100">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">
                            {{ __('dashAdm.quick.settings_title') }}
                        </div>
                        <div class="text-muted small">
                            {{ __('dashAdm.quick.settings_desc') }}
                        </div>
                    </div>
                </a>
            </div>

        </div>

    </div>
@endsection
