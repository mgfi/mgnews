@component('mail::message')
    # {{ __('passwords.reset_subject') }}

    {{ __('passwords.reset_line') }}

    @component('mail::button', ['url' => $url])
        {{ __('passwords.reset_action') }}
    @endcomponent

    {{ __('If you did not request a password reset, no further action is required.') }}
@endcomponent
