@extends('layouts.admin')

@section('content')
    <h1>Dodaj operatora</h1>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div>
            <label for="email">Email</label><br>
            <input id="email" type="email" name="email" required>
        </div>

        <fieldset style="margin-top:15px">
            <legend>Uprawnienia</legend>

            <label>
                <input type="checkbox" name="permissions[]" value="view_dashboard">
                Dashboard
            </label><br>

            <label>
                <input type="checkbox" name="permissions[]" value="newsletter_view">
                Newsletters – view
            </label><br>

            <label>
                <input type="checkbox" name="permissions[]" value="newsletter_edit">
                Newsletters – edit
            </label>
        </fieldset>

        <div style="margin-top:20px">
            <button type="submit">Zaproś operatora</button>
        </div>
    </form>
@endsection
