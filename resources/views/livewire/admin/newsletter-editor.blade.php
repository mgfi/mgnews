<div class="w-100">

    <h3 class="mb-4">✉️ Edycja Newslettera</h3>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Subject --}}
    <div class="mb-3">
        <label class="form-label">Subject</label>
        <input type="text" class="form-control" wire:model.defer="subject">
    </div>

    {{-- Preheader --}}
    <div class="mb-3">
        <label class="form-label">Preheader</label>
        <input type="text" class="form-control" wire:model.defer="preview_text">
    </div>

    {{-- Add row --}}
    <div class="mb-3">
        <label class="form-label">Dodaj wiersz</label>
        <div class="d-flex gap-2 flex-wrap">
            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addRowImgImg">IMG IMG</button>
            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addRowPP">P P</button>
            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addRowImgP">IMG P</button>
            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addRowPImg">P IMG</button>
            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addRowSingleImg">IMG</button>
            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addRowSingleP">P</button>
        </div>
    </div>

    {{-- Rows --}}
    @foreach ($rows as $rIndex => $row)
        <div class="border p-2 mb-2">
            <div class="d-flex gap-2 align-items-start">

                @foreach ($row as $cIndex => $el)
                    <div class="flex-fill border p-2">

                        {{-- IMAGE BLOCK --}}
                        @if ($el['type'] === 'img')
                            <input type="file" class="form-control mb-2"
                                wire:model="uploads.{{ $rIndex }}_{{ $cIndex }}">

                            @if (isset($uploads[$rIndex . '_' . $cIndex]))
                                <img src="{{ $uploads[$rIndex . '_' . $cIndex]->temporaryUrl() }}"
                                    class="img-fluid mb-2">
                            @endif

                            <input type="text" class="form-control" placeholder="Alt tekst"
                                wire:model.defer="rows.{{ $rIndex }}.{{ $cIndex }}.alt">
                        @endif

                        {{-- PARAGRAPH BLOCK (TRIX) --}}
                        @if ($el['type'] === 'p')
                            @php
                                $inputId = "trix_{$rIndex}_{$cIndex}";
                            @endphp

                            <input id="{{ $inputId }}" type="hidden" value="{{ $el['html'] ?? '' }}">

                            <div wire:ignore>
                                <trix-editor input="{{ $inputId }}" class="trix-content"
                                    data-row="{{ $rIndex }}" data-col="{{ $cIndex }}">
                                </trix-editor>
                            </div>
                        @endif


                    </div>
                @endforeach

                <button type="button" class="btn btn-danger btn-sm" wire:click="removeRow({{ $rIndex }})">
                    Usuń
                </button>

            </div>
        </div>
    @endforeach

    {{-- Save --}}
    <button class="btn btn-success mt-3" wire:click="save">
        💾 Zapisz
    </button>

</div>
@push('scripts')
    <script>
        document.addEventListener('trix-change', function(event) {
            const editor = event.target;
            const row = editor.dataset.row;
            const col = editor.dataset.col;

            if (row === undefined || col === undefined) return;

            const componentEl = editor.closest('[wire\\:id]');
            if (!componentEl) return;

            const componentId = componentEl.getAttribute('wire:id');

            Livewire.find(componentId)
                .set(`rows.${row}.${col}.html`, editor.value);
        });

        document.addEventListener('trix-file-accept', function(event) {
            event.preventDefault();
        });
    </script>
@endpush
