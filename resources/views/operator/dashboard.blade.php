@extends('layouts.operator')

@section('title', __('opeDash.title'))

@section('content')
    <h1 class="text-2xl font-bold mb-4">
        {{ __('opeDash.title') }}
    </h1>

    <p class="text-gray-600">
        {{ __('opeDash.welcome') }}
    </p>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="bg-white p-4 rounded border">
            <h2 class="font-semibold mb-2">
                {{ __('opeDash.cards.newsletters.title') }}
            </h2>
            <p class="text-sm text-gray-500">
                {{ __('opeDash.cards.newsletters.desc') }}
            </p>
        </div>

        <div class="bg-white p-4 rounded border">
            <h2 class="font-semibold mb-2">
                {{ __('opeDash.cards.subscribers.title') }}
            </h2>
            <p class="text-sm text-gray-500">
                {{ __('opeDash.cards.subscribers.desc') }}
            </p>
        </div>

    </div>
@endsection
