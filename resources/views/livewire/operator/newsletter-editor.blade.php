<div>
    <h1 class="text-xl font-bold mb-4">
        {{ $newsletterIssue->title }}
    </h1>

    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">
            Treść newslettera
        </label>

        <textarea wire:model.defer="content" rows="12" class="w-full border rounded p-2"></textarea>
    </div>
</div>
