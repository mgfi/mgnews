<nav class="list-group list-group-flush">

    <!-- DASHBOARD  -->

    @if(auth()->user()->hasPermission('view_dashboard'))
    <a href="{{ route('admin.dashboard') }}"
        class="list-group-item list-group-item-action">
        {{ __('layAdm.nav.dashboard') }}
    </a>
    @endif

    <!-- SUBSCRIBERS  -->

    @if(auth()->user()->hasPermission('subscriber_view'))
    <a href="{{ route('admin.subscribers.index') }}"
        class="list-group-item list-group-item-action">
        {{ __('layAdm.nav.subscribers') }}
    </a>
    @endif

    <!-- NEWSLETTERS  -->

    @if(auth()->user()->hasPermission('newsletter_view'))
    <a href="{{ route('admin.newsletters.index') }}"
        class="list-group-item list-group-item-action">
        {{ __('layAdm.nav.newsletters') }}
    </a>
    @endif

    <!-- SETTINGS  -->

    @if(auth()->user()->hasPermission('settings_view'))
    <a href="{{ route('admin.settings.index') }}"
        class="list-group-item list-group-item-action">
        {{ __('layAdm.nav.settings') }}
    </a>
    @endif
    <!-- AUDIT LOG  -->

    {{-- @if(auth()->user()->hasPermission('audit_view'))
    <a href="{{ route('admin.audit.index') }}"
        class="list-group-item list-group-item-action">
        {{ __('layAdm.nav.audit') }}
    </a>
    @endif --}}

</nav>