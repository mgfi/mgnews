<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-light">
    @php
        $navbar = $navbar ?? 'partials.navbar-admin';
        $sidebar = $sidebar ?? 'partials.sidebar-admin';
    @endphp
    {{-- TOP NAVBAR --}}
    @include($navbar)

    <div class="container-fluid">
        <div class="row">

            {{-- SIDEBAR --}}
            <aside class="col-md-2 p-0 bg-white border-end min-vh-100">
                @include($sidebar)
            </aside>

            {{-- MAIN CONTENT --}}
            <main class="col-md-10 p-4">

                @include('partials.flash')

                @if (session('redirect_after'))
                    <script>
                        setTimeout(() => {
                            window.location.href = "{{ session('redirect_after') }}";
                        }, 4000);
                    </script>
                @endif

                {{ $slot ?? '' }}
                @yield('content')

            </main>

        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>
