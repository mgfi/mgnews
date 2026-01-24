<p>Cześć!</p>

<p>Zostałeś zaproszony jako operator do panelu.</p>

<p>
    Kliknij poniższy link, aby ustawić hasło:
</p>

<p>
    <a href="{{ url('/invite/accept/' . $user->invite_token) }}">
        Ustaw hasło
    </a>
</p>

<p>
    Jeśli nie spodziewałeś się tej wiadomości — zignoruj ją.
</p>
