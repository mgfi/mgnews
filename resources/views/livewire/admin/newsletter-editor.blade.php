<div class="w-100">

    {{-- FILE: resources/views/livewire/admin/newsletter-editor.blade.php --}}
    <div class="alert alert-danger">
        TO JEST WIDOK: resources/views/livewire/admin/newsletter-editor.blade.php
    </div>

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>✉️ {{ __('admNewEdi.title') }}</h3>

        <div class="d-flex gap-2">
            <button class="btn btn-success" wire:click="save">
                💾 {{ __('admNewEdi.save') }}
            </button>

            <button class="btn btn-outline-secondary" wire:click="generate">
                ✨ {{ __('admNewEdi.generate') }}
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
            {{ __('admNewEdi.subject') }}
            <span class="text-muted" title="{{ __('admNewEdi.subjectHint') }}">ⓘ</span>
        </label>
        <input type="text" class="form-control" wire:model.defer="title_pl">
    </div>

    {{-- PREHEADER --}}
    <div class="mb-4">
        <label class="form-label">
            {{ __('admNewEdi.preview') }}
            <span class="text-muted" title="{{ __('admNewEdi.previewHint') }}">ⓘ</span>
        </label>
        <input type="text" class="form-control" wire:model.defer="preview_text_pl">
    </div>

    {{-- CAMPAIGN BLOCK --}}
    @if (!$newsletter->isSent())
        <div class="card mb-4">
            <div class="card-body">

                <label class="form-label fw-bold">
                    {{ __('admNewEdi.campaign') }}
                </label>

                <div class="d-flex gap-2 align-items-center">

                    <select wire:model="campaign_id" class="form-select">
                        <option value="">
                            {{ __('admNewEdi.campaign_none') }}
                        </option>

                        @foreach ($campaigns as $campaign)
                            <option value="{{ $campaign->id }}">
                                {{ $campaign->title }}
                            </option>
                        @endforeach
                    </select>

                    <button type="button"
                            class="btn btn-outline-primary"
                            wire:click="$toggle('creatingCampaign')">
                        ➕ {{ __('admNewEdi.campaign_new') }}
                    </button>
                </div>

                @if ($creatingCampaign)
                    <div class="d-flex gap-2 mt-3">
                        <input type="text"
                               class="form-control"
                               placeholder="{{ __('admNewEdi.campaign_name') }}"
                               wire:model.defer="newCampaignTitle">

                        <button type="button"
                                class="btn btn-success"
                                wire:click="createCampaign">
                            {{ __('admNewEdi.campaign_save') }}
                        </button>

                        <button type="button"
                                class="btn btn-outline-secondary"
                                wire:click="$set('creatingCampaign', false)">
                            {{ __('admNewEdi.campaign_cancel') }}
                        </button>
                    </div>
                @endif

            </div>
        </div>
    @endif

    {{-- ADD SECTION --}}
    <div class="mb-4">
        <label class="form-label fw-bold">
            {{ __('admNewEdi.addSection') }}
        </label>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-primary btn-sm" wire:click="addSection(1)">
                {{ __('admNewEdi.columns.1') }}
            </button>
            <button class="btn btn-outline-primary btn-sm" wire:click="addSection(2)">
                {{ __('admNewEdi.columns.2') }}
            </button>
            <button class="btn btn-outline-primary btn-sm" wire:click="addSection(3)">
                {{ __('admNewEdi.columns.3') }}
            </button>
        </div>
    </div>

    {{-- SECTIONS --}}
    @foreach ($sections as $sIndex => $section)
        <div class="border rounded p-3 mb-4 bg-light">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <strong>
                    {{ __('admNewEdi.section') }} {{ $sIndex + 1 }}
                    <span class="text-muted">
                        ({{ $section['columns'] }} {{ __('admNewEdi.columns.' . $section['columns']) }})
                    </span>
                </strong>

                <button class="btn btn-sm btn-outline-danger"
                        wire:click="removeSection({{ $sIndex }})">
                    {{ __('admNewEdi.removeSection') }}
                </button>
            </div>

            <div class="row g-3">
                @foreach ($section['columns_data'] as $cIndex => $column)
                    <div class="col-md-{{ 12 / $section['columns'] }}">
                        <div class="border rounded p-2 h-100 bg-white">

                            {{-- ADD BLOCK --}}
                            <div class="mb-2">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle w-100"
                                            data-bs-toggle="dropdown">
                                        + {{ __('admNewEdi.addBlock') }}
                                    </button>

                                    <ul class="dropdown-menu w-100">
                                        <li><a class="dropdown-item"
                                            wire:click="addBlock({{ $sIndex }}, {{ $cIndex }}, 'h1')">H1</a></li>
                                        <li><a class="dropdown-item"
                                            wire:click="addBlock({{ $sIndex }}, {{ $cIndex }}, 'h2')">H2</a></li>
                                        <li><a class="dropdown-item"
                                            wire:click="addBlock({{ $sIndex }}, {{ $cIndex }}, 'h3')">H3</a></li>
                                        <li><a class="dropdown-item"
                                            wire:click="addBlock({{ $sIndex }}, {{ $cIndex }}, 'p')">
                                            {{ __('admNewEdi.blocks.paragraph') }}</a></li>
                                        <li><a class="dropdown-item"
                                            wire:click="addBlock({{ $sIndex }}, {{ $cIndex }}, 'img')">
                                            {{ __('admNewEdi.blocks.image') }}</a></li>
                                        <li><a class="dropdown-item"
                                            wire:click="addBlock({{ $sIndex }}, {{ $cIndex }}, 'button')">
                                            {{ __('admNewEdi.blocks.button') }}</a></li>
                                    </ul>
                                </div>
                            </div>

                            {{-- BLOCKS --}}
                            @foreach ($column as $bIndex => $block)
                                <div class="border rounded p-2 mb-2">

                                    @if ($block['type'] === 'p')
                                        @php $inputId = "trix_{$sIndex}_{$cIndex}_{$bIndex}"; @endphp
                                        <input id="{{ $inputId }}" type="hidden"
                                               value="{{ $block['html'] ?? '' }}">
                                        <div wire:ignore>
                                            <trix-editor input="{{ $inputId }}"
                                                         data-section="{{ $sIndex }}"
                                                         data-column="{{ $cIndex }}"
                                                         data-block="{{ $bIndex }}">
                                            </trix-editor>
                                        </div>
                                    @endif

                                    @if (in_array($block['type'], ['h1','h2','h3']))
                                        <input type="text"
                                               class="form-control"
                                               placeholder="{{ strtoupper($block['type']) }}"
                                               wire:model.defer="sections.{{ $sIndex }}.columns_data.{{ $cIndex }}.{{ $bIndex }}.text">
                                    @endif

                                    @if ($block['type'] === 'img')
                                        <input type="file"
                                               class="form-control mb-2"
                                               wire:model="uploads.{{ $sIndex }}_{{ $cIndex }}_{{ $bIndex }}">

                                        @if (isset($uploads["{$sIndex}_{$cIndex}_{$bIndex}"]))
                                            <img src="{{ $uploads["{$sIndex}_{$cIndex}_{$bIndex}"]->temporaryUrl() }}"
                                                 class="img-fluid mb-2 rounded">
                                        @endif

                                        <input type="text"
                                               class="form-control"
                                               placeholder="{{ __('admNewEdi.alt') }}"
                                               wire:model.defer="sections.{{ $sIndex }}.columns_data.{{ $cIndex }}.{{ $bIndex }}.alt">
                                    @endif

                                    @if ($block['type'] === 'button')
                                        <input type="text"
                                               class="form-control mb-1"
                                               placeholder="{{ __('admNewEdi.label') }}"
                                               wire:model.defer="sections.{{ $sIndex }}.columns_data.{{ $cIndex }}.{{ $bIndex }}.label">

                                        <input type="text"
                                               class="form-control"
                                               placeholder="{{ __('admNewEdi.url') }}"
                                               wire:model.defer="sections.{{ $sIndex }}.columns_data.{{ $cIndex }}.{{ $bIndex }}.url">
                                    @endif

                                    <button class="btn btn-sm btn-outline-danger mt-2"
                                            wire:click="removeBlock({{ $sIndex }}, {{ $cIndex }}, {{ $bIndex }})">
                                        {{ __('admNewEdi.removeBlock') }}
                                    </button>

                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- FOOTER --}}
    <div class="alert alert-secondary mt-5">
        <strong>{{ __('admNewEdi.footerTitle') }}</strong><br>
        {{ __('admNewEdi.footerInfo') }}
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
