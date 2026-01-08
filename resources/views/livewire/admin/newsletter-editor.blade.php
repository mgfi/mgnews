<div class="container">

    <h3 class="mb-4">✉️ Edycja Newslettera</h3>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label">Subject</label>
        <input type="text" class="form-control" wire:model.defer="subject">
    </div>

    <div class="mb-3">
        <label class="form-label">Preheader</label>
        <input type="text" class="form-control" wire:model.defer="preview_text">
    </div>

    <div class="mb-3">
        <label class="form-label">Content (JSON – tymczasowo)</label>
        <textarea class="form-control" rows="10" wire:model.defer="rows"></textarea>
    </div>

    <button class="btn btn-success" wire:click="save">
        💾 Zapisz
    </button>

</div>
