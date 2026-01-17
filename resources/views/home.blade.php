@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">

            <h1 class="mb-4">
                {{ __('home.title') }}
            </h1>

            <p class="lead mb-5">
                {{ __('home.lead') }}
            </p>

            <livewire:newsletter.subscribe-form />

        </div>
    </div>
@endsection
