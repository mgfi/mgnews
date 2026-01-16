<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">

        <span class="navbar-brand mb-0">
            Admin Panel
        </span>

        <div class="d-flex align-items-center gap-3">

            {{-- LOCALE SWITCH --}}
            <form method="POST" action="{{ route('locale.switch', '__LOCALE__') }}" id="locale-form">
                @csrf

                <select class="form-select form-select-sm" style="width:auto"
                    onchange="
                            const form = document.getElementById('locale-form');
                            form.action = '{{ url('/locale') }}/' + this.value;
                            form.submit();
                        ">
                    <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>
                        🇬🇧 EN
                    </option>
                    <option value="pl" {{ app()->getLocale() === 'pl' ? 'selected' : '' }}>
                        🇵🇱 PL
                    </option>
                </select>
            </form>

            {{-- USER --}}
            <span class="text-white small">
                {{ auth()->user()->name }}
            </span>

            {{-- LOGOUT --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-sm">
                    Wyloguj
                </button>
            </form>

        </div>
    </div>
</nav>
