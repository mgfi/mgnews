<ul class="nav flex-column p-3 gap-1">

    {{-- Dashboard --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'fw-bold' : '' }}"
            href="{{ route('admin.dashboard') }}">
            Dashboard
        </a>
    </li>

    {{-- Subscribers --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.subscribers.*') ? 'fw-bold' : '' }}"
            href="{{ route('admin.subscribers.index') }}">
            Subskrybenci
        </a>
    </li>

    {{-- Newsletters --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.newsletters.*') ? 'fw-bold' : '' }}"
            href="{{ route('admin.newsletters.index') }}">
            Newslettery
        </a>
    </li>

    <hr class="my-2">

    {{-- Settings (ACTIVE) --}}
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'fw-bold' : '' }}"
            href="{{ route('admin.settings.index') }}">
            ⚙️ Settings
        </a>
    </li>

    {{-- Statistics (DISABLED / COMING SOON) --}}
    <li class="nav-item">
        <span class="nav-link text-muted" style="cursor:not-allowed;">
            📊 Statistics
        </span>
    </li>

</ul>
