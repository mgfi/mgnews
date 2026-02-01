<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('layOpe.title') }} | {{ config('app.name') }}</title>

    {{-- TEN SAM FRONT CO ADMIN --}}
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="bg-light">

    {{-- TOP NAVBAR --}}
    @include('operator.partials.navbar')

    <div class="container-fluid">
        <div class="row">

            {{-- SIDEBAR --}}
            <aside class="col-md-2 p-0 bg-white border-end min-vh-100">
                @include('operator.partials.sidebar')
            </aside>

            {{-- MAIN CONTENT --}}
            <main class="col-md-10 p-4">

                {{-- FLASH --}}
                @include('partials.flash')

                {{-- CONTENT --}}
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset

            </main>

        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>
