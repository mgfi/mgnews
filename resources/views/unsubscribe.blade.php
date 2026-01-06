@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h1 class="mb-4">Zarządzanie subskrypcją</h1>

            <p class="text-muted">
                Adres e-mail: <strong>{{ $subscriber->email }}</strong>
            </p>

            <form method="POST" action="{{ route('unsubscribe.process', $subscriber->unsubscribe_token) }}">
                @csrf

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="action" id="unsubscribe" value="unsubscribe"
                            required>
                        <label class="form-check-label" for="unsubscribe">
                            <strong>Cofam zgodę na otrzymywanie newslettera</strong><br>
                            <small class="text-muted">
                                (art. 7 ust. 3 RODO)
                            </small>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="action" id="erase" value="erase"
                            required>
                        <label class="form-check-label" for="erase">
                            <strong>Żądam całkowitego usunięcia moich danych osobowych</strong><br>
                            <small class="text-muted">
                                (art. 17 RODO – prawo do bycia zapomnianym)
                            </small>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger">
                    Zatwierdź wybór
                </button>

            </form>
        </div>
    </div>
@endsection
