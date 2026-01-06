@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">

            <h1 class="mb-4">Newsletter</h1>

            <p class="lead mb-5">
                Zapisz się do newslettera
            </p>

            <livewire:newsletter.subscribe-form />

        </div>
    </div>
@endsection
