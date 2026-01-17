<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('layAdm.title') }} | {{ config('app.name') }}</title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="bg-light">

    @include('admin.partials.navbar')

    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-2 p-0 bg-white border-end min-vh-100">
                @include('admin.partials.sidebar')
            </aside>

            <main class="col-md-10 p-4">
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
