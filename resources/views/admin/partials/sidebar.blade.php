<ul class="nav flex-column p-3 gap-1">

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'fw-bold' : '' }}"
            href="{{ route('admin.dashboard') }}">
            {{ __('admin.dashboard') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.subscribers.*') ? 'fw-bold' : '' }}"
            href="{{ route('admin.subscribers.index') }}">
            {{ __('admin.subscribers') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.newsletters.*') ? 'fw-bold' : '' }}"
            href="{{ route('admin.newsletters.index') }}">
            {{ __('admin.newsletters') }}
        </a>
    </li>

    <hr class="my-2">

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'fw-bold' : '' }}"
            href="{{ route('admin.settings.index') }}">
            ⚙️ {{ __('admin.settings') }}
        </a>
    </li>

</ul>
