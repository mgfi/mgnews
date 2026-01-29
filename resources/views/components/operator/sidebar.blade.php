<aside class="w-64 bg-white border-r flex flex-col">

    {{-- Logo / Title --}}
    <div class="h-16 flex items-center px-6 font-bold text-lg border-b">
        {{ __('layOpe.title') }}
    </div>

    {{-- Menu --}}
    <nav class="flex-1 py-4 space-y-1">

        <a href="{{ route('operator.dashboard') }}" class="block px-6 py-2 hover:bg-gray-100">
            {{ __('comOpeSid.menu.dashboard') }}
        </a>

        @if (auth()->user()->hasPermission('newsletter_view'))
            <a href="{{ route('operator.newsletters.index') }}" class="block px-6 py-2 hover:bg-gray-100">
                {{ __('comOpeSid.menu.newsletters') }}
            </a>
        @endif

        @if (auth()->user()->hasPermission('subscriber_view'))
            <a href="{{ route('operator.subscribers.index') }}" class="block px-6 py-2 hover:bg-gray-100">
                {{ __('comOpeSid.menu.subscribers') }}
            </a>
        @endif

    </nav>

</aside>
