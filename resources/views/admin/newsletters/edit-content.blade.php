@extends('layouts.admin')

@section('content')
    <h1>✉️ Edycja treści newslettera</h1>

    <livewire:admin.newsletter-editor :newsletter="$newsletter" />
@endsection
