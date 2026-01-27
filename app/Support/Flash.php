<?php

namespace App\Support;

class Flash
{
    /**
     * Flash success message.
     * Optional redirect_after URL.
     */
    public static function success(string $message, ?string $redirectAfter = null): void
    {
        session()->flash('success', $message);

        if ($redirectAfter) {
            session()->flash('redirect_after', $redirectAfter);
        }
    }

    /**
     * Flash error message.
     */
    public static function error(string $message): void
    {
        session()->flash('error', $message);
    }

    /**
     * Flash warning message.
     */
    public static function warning(string $message): void
    {
        session()->flash('warning', $message);
    }
}
