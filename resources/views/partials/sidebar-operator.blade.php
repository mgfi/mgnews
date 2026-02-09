<nav class="nav flex-column p-3 gap-1">

    <a class="nav-link" href="{{ route('operator.dashboard') }}">
        {{ __('parSidOpe.dashboard') }}
    </a>

    <a class="nav-link" href="{{ route('operator.newsletters.index') }}">
        {{ __('parSidOpe.newsletters') }}
    </a>

    <a class="nav-link" href="{{ route('operator.subscribers.index') }}">
        {{ __('parSidOpe.subscribers') }}
    </a>

</nav>
