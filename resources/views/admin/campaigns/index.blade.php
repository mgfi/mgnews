@extends('layouts.panel')

@section('content')
    <div class="container-fluid">

        {{-- FILE: resources/views/admin/campaigns/index.blade.php --}}
        <div class="alert alert-danger">
            TO JEST WIDOK: resources/views/admin/campaigns/index.blade.php
        </div>

        @php
            $breadcrumbs = [
                [
                    'label' => __('breadcrumbs.dashboard'),
                    'route' => 'admin.dashboard',
                ],
                [
                    'label' => __('breadcrumbs.campaigns'),
                ],
            ];
        @endphp

        @include('partials.breadcrumbs')

        <h1 class="h4 mb-4">
            {{ __('breadcrumbs.campaigns') }}
        </h1>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('dashAdm.table.topic') }}</th>
                        <th>{{ __('dashAdm.table.active') }}</th>
                        <th>{{ __('dashAdm.table.newsletters') }}</th>
                        <th>{{ __('dashAdm.table.last_sent') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($campaigns as $campaign)
                        <tr>
                            <td>
                                <a href="{{ route('admin.campaigns.show', $campaign) }}"
                                    class="fw-semibold text-decoration-none text-dark">
                                    {{ $campaign->title }}
                                </a>
                            </td>
                            <td>
                                <span class="badge {{ $campaign->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $campaign->is_active ? __('dashAdm.common.yes') : __('dashAdm.common.no') }}
                                </span>
                            </td>
                            <td>{{ $campaign->newsletters_count }}</td>
                            <td>{{ $campaign->last_sent_at ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
