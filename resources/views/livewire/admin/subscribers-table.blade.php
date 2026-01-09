<div class="container">

    <h3 class="mb-4">📬 Subskrybenci</h3>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ADD SUBSCRIBER --}}
    <div class="card mb-4">
        <div class="card-body d-flex gap-2">
            <input type="email" class="form-control" placeholder="email@example.com" wire:model.defer="email">
            <button class="btn btn-primary" wire:click="add">
                ➕ Dodaj
            </button>
        </div>
    </div>

    {{-- LIST --}}
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Email</th>
                <th>Status</th>
                <th>Źródło</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($subscribers as $subscriber)
                <tr>
                    <td>{{ $subscriber->id }}</td>
                    <td>{{ $subscriber->email }}</td>
                    <td>
                        @if ($subscriber->is_active)
                            <span class="badge bg-success">aktywny</span>
                        @else
                            <span class="badge bg-secondary">nieaktywny</span>
                        @endif
                    </td>
                    <td>{{ $subscriber->source ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Brak subskrybentów
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $subscribers->links() }}
</div>
