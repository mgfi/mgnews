<ul class="nav flex-column p-3 gap-1">

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'fw-bold' : '' }}"
            href="{{ route('admin.dashboard') }}">
            Dashboard
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.subscribers.*') ? 'fw-bold' : '' }}"
            href="{{ route('admin.subscribers.index') }}">
            Subskrybenci
        </a>
    </li>

</ul>
