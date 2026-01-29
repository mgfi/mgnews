<header class="h-16 bg-white border-b flex items-center justify-between px-6">

    {{-- Left side --}}
    <div class="text-sm text-gray-600">
        {{ __('comOpeHea.logged_as') }}:
        <span class="font-medium text-gray-900">
            {{ auth()->user()->email }}
        </span>
    </div>

    {{-- Right side --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button
            type="submit"
            class="text-sm text-red-600 hover:underline"
        >
            {{ __('comOpeHea.actions.logout') }}
        </button>
    </form>

</header>
