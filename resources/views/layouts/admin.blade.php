<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | {{ config('app.name') }}</title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light">

    @include('admin.partials.navbar')

    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-2 p-0 bg-white border-end min-vh-100">
                @include('admin.partials.sidebar')
            </aside>

            <main class="col-md-10 p-4">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>
