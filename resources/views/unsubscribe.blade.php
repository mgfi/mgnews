@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h1 class="mb-4">
                {{ __('unsubInd.title') }}
            </h1>

            <p class="text-muted">
                {{ __('unsubInd.emailLabel') }}
                <strong>{{ $subscriber->email }}</strong>
            </p>

            <form method="POST" action="{{ route('unsubscribe.process', $subscriber->unsubscribe_token) }}">
                @csrf

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="action" id="unsubscribe" value="unsubscribe"
                            required>
                        <label class="form-check-label" for="unsubscribe">
                            <strong>{{ __('unsubInd.unsubscribeTitle') }}</strong><br>
                            <small class="text-muted">
                                {{ __('unsubInd.unsubscribeHint') }}
                            </small>
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="action" id="erase" value="erase"
                            required>
                        <label class="form-check-label" for="erase">
                            <strong>{{ __('unsubInd.eraseTitle') }}</strong><br>
                            <small class="text-muted">
                                {{ __('unsubInd.eraseHint') }}
                            </small>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger">
                    {{ __('unsubInd.submit') }}
                </button>

            </form>
        </div>
    </div>
@endsection
