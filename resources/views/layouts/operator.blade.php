<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@yield('title', __('layOpe.title'))</title>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900">

<div class="min-h-screen flex">

    {{-- Sidebar --}}
    <x-operator.sidebar />

    <div class="flex-1 flex flex-col">

        {{-- Header --}}
        <x-operator.header />

        {{-- Main content --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

        {{-- Footer --}}
        <x-operator.footer />

    </div>
</div>

@livewireScripts
</body>
</html>
