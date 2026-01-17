@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h1 class="mb-4">
                {{ __('privacy.title') }}
            </h1>

            <p>
                {{ __('privacy.intro') }}
            </p>

            <h3>{{ __('privacy.adminTitle') }}</h3>
            <p>
                {!! __('privacy.adminText', ['app' => config('app.name')]) !!}
            </p>

            <h3>{{ __('privacy.scopeTitle') }}</h3>
            <p>
                {{ __('privacy.scopeIntro') }}
            </p>
            <ul>
                @foreach (__('privacy.scopeList') as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>

            <h3>{{ __('privacy.purposeTitle') }}</h3>
            <p>
                {{ __('privacy.purposeIntro') }}
            </p>
            <ul>
                @foreach (__('privacy.purposeList') as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>

            <h3>{{ __('privacy.legalTitle') }}</h3>
            <p>
                {{ __('privacy.legalIntro') }}
            </p>
            <ul>
                @foreach (__('privacy.legalList') as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>

            <h3>{{ __('privacy.storageTitle') }}</h3>
            <p>
                {{ __('privacy.storageText') }}
            </p>

            <h3>{{ __('privacy.rightsTitle') }}</h3>
            <p>
                {{ __('privacy.rightsIntro') }}
            </p>
            <ul>
                @foreach (__('privacy.rightsList') as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>

            <h3>{{ __('privacy.unsubscribeTitle') }}</h3>
            <p>
                {{ __('privacy.unsubscribeText') }}
            </p>

            <h3>{{ __('privacy.contactTitle') }}</h3>
            <p>
                {!! __('privacy.contactText', ['email' => config('mail.from.address')]) !!}
            </p>

            <p class="mt-4 text-muted">
                {{ __('privacy.updated', ['date' => now()->format('d.m.Y')]) }}
            </p>

        </div>
    </div>
@endsection
