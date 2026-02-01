<ul class="nav flex-column p-3 gap-1">

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operator.dashboard') ? 'fw-bold' : '' }}"
            href="{{ route('operator.dashboard') }}">
            {{ __('opeParSide.dashboard') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operator.subscribers.*') ? 'fw-bold' : '' }}"
            href="{{ route('operator.subscribers.index') }}">
            {{ __('opeParSide.subscribers') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('operator.newsletters.*') ? 'fw-bold' : '' }}"
            href="{{ route('operator.newsletters.index') }}">
            {{ __('opeParSide.newsletters') }}
        </a>
    </li>

</ul>
