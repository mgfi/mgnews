<div class="w-100">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>✉️ {{ __('livAdmNewEdi.title') }}</h3>

        <div class="d-flex gap-2">
            <button class="btn btn-success" wire:click="save">
                💾 {{ __('livAdmNewEdi.save') }}
            </button>

            <button class="btn btn-outline-secondary" wire:click="generate">
                ✨ {{ __('livAdmNewEdi.generate') }}
            </button>
        </div>
    </div>

    {{-- FLASH --}}
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- SUBJECT --}}
    <div class="mb-3">
        <label class="form-label">
            {{ __('livAdmNewEdi.subject') }}
            <span class="text-muted" title="{{ __('livAdmNewEdi.subjectHint') }}">ⓘ</span>
        </label>
        <input type="text" class="form-control" wire:model.defer="title_pl">
    </div>

    {{-- PREHEADER --}}
    <div class="mb-4">
        <label class="form-label">
            {{ __('livAdmNewEdi.preview') }}
            <span class="text-muted" title="{{ __('livAdmNewEdi.previewHint') }}">ⓘ</span>
        </label>
        <input type="text" class="form-control" wire:model.defer="preview_text_pl">
    </div>

    {{-- ADD SECTION --}}
    <div class="mb-4">
        <label class="form-label fw-bold">
            {{ __('livAdmNewEdi.addSection') }}
        </label>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-primary btn-sm" wire:click="addSection(1)">
                {{ __('livAdmNewEdi.columns.1') }}
            </button>
            <button class="btn btn-outline-primary btn-sm" wire:click="addSection(2)">
                {{ __('livAdmNewEdi.columns.2') }}
            </button>
            <button class="btn btn-outline-primary btn-sm" wire:click="addSection(3)">
                {{ __('livAdmNewEdi.columns.3') }}
            </button>
        </div>
    </div>

    {{-- SECTIONS --}}
    @foreach ($sections as $sIndex => $section)
        <div class="border rounded p-3 mb-4 bg-light">

            {{-- SECTION HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <strong>
                    {{ __('livAdmNewEdi.section') }} {{ $sIndex + 1 }}
                    <span class="text-muted">
                        ({{ $section['columns'] }} {{ __('livAdmNewEdi.columns.' . $section['columns']) }})
                    </span>
                </strong>

                <button class="btn btn-sm btn-outline-danger" wire:click="removeSection({{ $sIndex }})">
                    {{ __('livAdmNewEdi.removeSection') }}
                </button>
            </div>

            {{-- COLUMNS --}}
            <div class="row g-3">
                @foreach ($section['columns_data'] as $cIndex => $column)
                    <div class="col-md-{{ 12 / $section['columns'] }}">
                        <div class="border rounded p-2 h-100 bg-white">

                            {{-- ADD BLOCK --}}
                            <div class="mb-2">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100"
                                        data-bs-toggle="dropdown">
                                        + {{ __('livAdmNewEdi.addBlock') }}
                                    </button>

                                    <ul class="dropdown-menu w-100">
                                        <li>
                                            <a class="dropdown-item"
                                                wire:click="addBlock({{ $sIndex }}, {{ $cIndex }}, 'h1')">
                                                H1
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                wire:click="addBlock({{ $sIndex }}, {{ $cIndex }}, 'h2')">
                                                H2
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                wire:click="addBlock({{ $sIndex }}, {{ $cIndex }}, 'h3')">
                                                H3
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                wire:click="addBlock({{ $sIndex }}, {{ $cIndex }}, 'p')">
                                                {{ __('livAdmNewEdi.blocks.paragraph') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                wire:click="addBlock({{ $sIndex }}, {{ $cIndex }}, 'img')">
                                                {{ __('livAdmNewEdi.blocks.image') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                wire:click="addBlock({{ $sIndex }}, {{ $cIndex }}, 'button')">
                                                {{ __('livAdmNewEdi.blocks.button') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- BLOCKS --}}
                            @foreach ($column as $bIndex => $block)
                                <div class="border rounded p-2 mb-2">

                                    {{-- PARAGRAPH --}}
                                    @if ($block['type'] === 'p')
                                        @php
                                            $inputId = "trix_{$sIndex}_{$cIndex}_{$bIndex}";
                                        @endphp

                                        <input id="{{ $inputId }}" type="hidden"
                                            value="{{ $block['html'] ?? '' }}">

                                        <div wire:ignore>
                                            <trix-editor input="{{ $inputId }}"
                                                data-section="{{ $sIndex }}" data-column="{{ $cIndex }}"
                                                data-block="{{ $bIndex }}">
                                            </trix-editor>
                                        </div>
                                    @endif

                                    {{-- HEADERS --}}
                                    @if (in_array($block['type'], ['h1', 'h2', 'h3']))
                                        <input type="text" class="form-control"
                                            placeholder="{{ strtoupper($block['type']) }}"
                                            wire:model.defer="sections.{{ $sIndex }}.columns_data.{{ $cIndex }}.{{ $bIndex }}.text">
                                    @endif

                                    {{-- IMAGE --}}
                                    @if ($block['type'] === 'img')
                                        <input type="file" class="form-control mb-2"
                                            wire:model="uploads.{{ $sIndex }}_{{ $cIndex }}_{{ $bIndex }}">

                                        @if (isset($uploads["{$sIndex}_{$cIndex}_{$bIndex}"]))
                                            <img src="{{ $uploads["{$sIndex}_{$cIndex}_{$bIndex}"]->temporaryUrl() }}"
                                                class="img-fluid mb-2 rounded">
                                        @endif

                                        <input type="text" class="form-control"
                                            placeholder="{{ __('livAdmNewEdi.alt') }}"
                                            wire:model.defer="sections.{{ $sIndex }}.columns_data.{{ $cIndex }}.{{ $bIndex }}.alt">
                                    @endif

                                    {{-- BUTTON --}}
                                    @if ($block['type'] === 'button')
                                        <input type="text" class="form-control mb-1"
                                            placeholder="{{ __('livAdmNewEdi.label') }}"
                                            wire:model.defer="sections.{{ $sIndex }}.columns_data.{{ $cIndex }}.{{ $bIndex }}.label">

                                        <input type="text" class="form-control"
                                            placeholder="{{ __('livAdmNewEdi.url') }}"
                                            wire:model.defer="sections.{{ $sIndex }}.columns_data.{{ $cIndex }}.{{ $bIndex }}.url">
                                    @endif

                                    <button class="btn btn-sm btn-outline-danger mt-2"
                                        wire:click="removeBlock({{ $sIndex }}, {{ $cIndex }}, {{ $bIndex }})">
                                        {{ __('livAdmNewEdi.removeBlock') }}
                                    </button>

                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- FOOTER INFO --}}
    <div class="alert alert-secondary mt-5">
        <strong>{{ __('livAdmNewEdi.footerTitle') }}</strong><br>
        {{ __('livAdmNewEdi.footerInfo') }}
    </div>

</div>

{{-- TRIX + LIVEWIRE SYNC --}}
@push('scripts')
    <script>
        document.addEventListener('trix-change', function(event) {
            const editor = event.target;

            const section = editor.dataset.section;
            const column = editor.dataset.column;
            const block = editor.dataset.block;

            if (section === undefined || column === undefined || block === undefined) {
                return;
            }

            const componentEl = editor.closest('[wire\\:id]');
            if (!componentEl) return;

            const componentId = componentEl.getAttribute('wire:id');

            Livewire.find(componentId).set(
                `sections.${section}.columns_data.${column}.${block}.html`,
                editor.value
            );
        });

        document.addEventListener('trix-file-accept', function(event) {
            event.preventDefault();
        });
    </script>
@endpush
