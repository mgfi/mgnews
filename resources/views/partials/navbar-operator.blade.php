<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">

        <span class="navbar-brand mb-0">
            {{ __('opeParNav.operatorPanel') }}
        </span>

        <div class="d-flex align-items-center gap-3">

            {{-- LOCALE SWITCH --}}
            <div class="dropdown">

                <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-2"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('images/flags/' . app()->getLocale() . '.svg') }}" alt="{{ app()->getLocale() }}"
                        width="18" height="12">

                    <span class="text-uppercase">
                        {{ app()->getLocale() }}
                    </span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    {{-- EN --}}
                    <li>
                        <form method="POST" action="{{ url('/locale/en') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2">
                                <img src="{{ asset('images/flags/en.svg') }}" width="18" height="12">
                                {{ __('opeParNav.languageEn') }}
                            </button>
                        </form>
                    </li>

                    {{-- PL --}}
                    <li>
                        <form method="POST" action="{{ url('/locale/pl') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center gap-2">
                                <img src="{{ asset('images/flags/pl.svg') }}" width="18" height="12">
                                {{ __('opeParNav.languagePl') }}
                            </button>
                        </form>
                    </li>

                </ul>
            </div>

            {{-- USER --}}
            <span class="text-white small">
                {{ auth()->user()->name }}
            </span>

            {{-- LOGOUT --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-sm">
                    {{ __('opeParNav.logout') }}
                </button>
            </form>

        </div>
    </div>
</nav>
