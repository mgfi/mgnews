@extends('layouts.panel')

@section('content')
    <div class="container-fluid">

        {{-- FILE: resources/views/admin/newsletters/edit-content.blade.php --}}
        <div class="alert alert-danger">
            TO JEST WIDOK: resources/views/admin/newsletters/edit-content.blade.php
        </div>

        @php
            $breadcrumbs = [
                [
                    'label' => __('breadcrumbs.newsletters'),
                    'route' => 'admin.newsletters.index',
                ],
                [
                    'label' => __('breadcrumbs.newsletter'),
                ],
            ];
        @endphp

        @include('partials.breadcrumbs')

        <h1 class="h4 mb-4">
            {{ __('breadcrumbs.newsletter') }}
        </h1>

        <livewire:admin.newsletter-editor :newsletter="$newsletter" />

    </div>
@endsection
