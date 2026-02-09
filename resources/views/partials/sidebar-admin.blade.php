<nav class="nav flex-column p-3 gap-1">

    <a class="nav-link" href="{{ route('admin.dashboard') }}">
        {{ __('parSidAdm.dashboard') }}
    </a>

    <a class="nav-link" href="{{ route('admin.newsletters.index') }}">
        {{ __('parSidAdm.newsletters') }}
    </a>

    <a class="nav-link" href="{{ route('admin.subscribers.index') }}">
        {{ __('parSidAdm.subscribers') }}
    </a>

    <a class="nav-link" href="{{ route('admin.operators.index') }}">
        {{ __('parSidAdm.operators') }}
    </a>

    <a class="nav-link" href="{{ route('admin.settings.index') }}">
        {{ __('parSidAdm.settings') }}
    </a>

    <a class="nav-link" href="{{ route('admin.audit-logs.index') }}">
        {{ __('parSidAdm.audit') }}
    </a>

</nav>
