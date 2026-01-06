<div class="newsletter-form">
    <form wire:submit.prevent="submit" class="row g-2">
        <div class="col-12 col-md-8">
            <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Twój adres e-mail"
                wire:model.defer="email" required>

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-12 col-md-4 d-grid">
            <button type="submit" class="btn btn-primary">
                Zapisz się
            </button>
        </div>
    </form>

    @if (session()->has('newsletter_message'))
        <div class="alert alert-info mt-3">
            {{ session('newsletter_message') }}
        </div>
    @endif
</div>
