@extends('layouts.panel')

@section('content')
    <h1>✉️ {{ __('admNewEdi.title') }}</h1>

    <livewire:admin.newsletter-editor :newsletter="$newsletter" />
@endsection
