<div class="container">

    <h3 class="mb-4">
        📬 {{ __('livAdmSubInd.title') }}
    </h3>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ADD SUBSCRIBER --}}
    <div class="card mb-4">
        <div class="card-body d-flex gap-2">
            <input type="email" class="form-control" placeholder="{{ __('livAdmSubInd.addPlaceholder') }}"
                wire:model.defer="email">

            <button class="btn btn-primary" wire:click="add">
                ➕ {{ __('livAdmSubInd.add') }}
            </button>
        </div>
    </div>

    {{-- LIST --}}
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>{{ __('livAdmSubInd.table.id') }}</th>
                <th>{{ __('livAdmSubInd.table.email') }}</th>
                <th>{{ __('livAdmSubInd.table.status') }}</th>
                <th>{{ __('livAdmSubInd.table.source') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($subscribers as $subscriber)
                <tr>
                    <td>{{ $subscriber->id }}</td>
                    <td>{{ $subscriber->email }}</td>
                    <td>
                        @if ($subscriber->is_active)
                            <span class="badge bg-success">
                                {{ __('livAdmSubInd.status.active') }}
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                {{ __('livAdmSubInd.status.inactive') }}
                            </span>
                        @endif
                    </td>
                    <td>{{ $subscriber->source ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        {{ __('livAdmSubInd.empty') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $subscribers->links() }}
</div>
