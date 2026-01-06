<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0">
            Admin Panel
        </span>

        <div class="d-flex align-items-center gap-2">
            <span class="text-white small">
                {{ auth()->user()->name }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-sm">
                    Wyloguj
                </button>
            </form>
        </div>
    </div>
</nav>
