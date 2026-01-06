@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Dashboard</h1>

    {{-- KPI --}}
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Subskrybenci</div>
                    <div class="fs-3 fw-bold">
                        {{ \App\Models\Subscriber::count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Użytkownicy</div>
                    <div class="fs-3 fw-bold">
                        {{ \App\Models\User::count() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Admini</div>
                    <div class="fs-3 fw-bold">
                        {{ \App\Models\User::where('utype', 'ADM')->count() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- OSTATNI SUBSKRYBENCI --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">
            Ostatni subskrybenci
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\App\Models\Subscriber::latest()->limit(5)->get() as $sub)
                        <tr>
                            <td>{{ $sub->email }}</td>
                            <td>{{ $sub->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- SZYBKIE AKCJE --}}
    <div class="card shadow-sm">
        <div class="card-header fw-bold">
            Szybkie akcje
        </div>
        <div class="card-body d-flex gap-2">
            <a href="{{ route('admin.subscribers.index') }}" class="btn btn-primary">
                Zarządzaj subskrybentami
            </a>
        </div>
    </div>
@endsection
