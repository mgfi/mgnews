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

    {{-- TOP NAVBAR (opcjonalnie inny niż admin) --}}
    <x-operator.header />

    <div class="container-fluid">
        <div class="row">

            {{-- SIDEBAR --}}
            <aside class="col-md-2 p-0 bg-white border-end min-vh-100">
                <x-operator.sidebar />
            </aside>

            {{-- MAIN CONTENT --}}
            <main class="col-md-10 p-4">
                @yield('content')
            </main>

        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>
