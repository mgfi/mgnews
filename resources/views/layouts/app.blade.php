<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Newsletter') }}</title>

    <!-- Bootstrap SCSS + JS -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    @livewireStyles
</head>

<body>

    {{-- HEADER --}}
    <header class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Newsletter') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-link nav-link">Wyloguj</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Zaloguj</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Rejestracja</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </header>

    {{-- CONTENT --}}
    <main class="container my-5">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="border-top py-4">
        <div class="container text-center text-muted">
            © {{ date('Y') }} {{ config('app.name') }}
        </div>
    </footer>

    @livewireScripts
</body>

</html>
