@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">
        {{ __('admSubInd.title') }}
    </h1>

    <livewire:admin.subscribers-table />
@endsection
