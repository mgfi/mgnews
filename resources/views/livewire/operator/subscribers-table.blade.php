<div> {{-- ✅ JEDEN ROOT ELEMENT --}}

    <div class="card shadow-sm">

        {{-- HEADER --}}
        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            {{-- SEARCH --}}
            <input type="text" class="form-control w-50" wire:model.debounce.400ms="search"
                placeholder="🔍 Wyszukaj email">

            {{-- ADD --}}
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubscriberModal">
                ➕ Dodaj
            </button>
        </div>

        {{-- TABLE --}}
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Dodany</th>
                        <th class="text-end">Akcje</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($subscribers as $subscriber)
                        <tr>
                            <td>{{ $subscriber->email }}</td>

                            <td>
                                @if ($subscriber->is_active)
                                    <span class="badge bg-success">Aktywny</span>
                                @else
                                    <span class="badge bg-secondary">Nieaktywny</span>
                                @endif
                            </td>

                            <td>{{ $subscriber->created_at->format('Y-m-d') }}</td>

                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="confirm('Usunąć subskrybenta {{ $subscriber->email }}?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $subscriber->id }})">
                                    🗑
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Brak subskrybentów
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- FOOTER --}}
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing {{ $subscribers->firstItem() }}
                to {{ $subscribers->lastItem() }}
                of {{ $subscribers->total() }} results
            </div>

            <div>
                {{ $subscribers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- MODAL (WCIĄŻ TEN SAM ROOT) --}}
    <div wire:ignore.self class="modal fade" id="addSubscriberModal" tabindex="-1">
        <div class="modal-dialog">
            <form wire:submit.prevent="add" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Dodaj subskrybenta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="email" class="form-control" wire:model.defer="email" placeholder="email@domena.pl"
                        required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Anuluj
                    </button>

                    <button class="btn btn-primary">
                        ➕ Dodaj
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', () => {
                const modal = bootstrap.Modal.getInstance(
                    document.getElementById('addSubscriberModal')
                );
                modal?.hide();
            });
        });
    </script>

</div>
