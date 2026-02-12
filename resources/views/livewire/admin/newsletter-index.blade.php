<div class="container">
    <div class="alert alert-danger">To jest widok : resources\views\livewire\admin\newsletter-index.blade.php</div>
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">📨 {{ __('livAdmNewInd.title') }}</h3>

        <div class="d-flex gap-2">
            <button wire:click="create" class="btn btn-success">
                ➕ {{ __('livAdmNewInd.create') }}
            </button>

            <button class="btn btn-outline-primary" disabled>
                ➕ {{ __('livAdmNewInd.createCampaign') }}
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">

            <table class="table table-striped table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('livAdmNewInd.table.id') }}</th>
                        <th>{{ __('livAdmNewInd.table.subject') }}</th>
                        <th>{{ __('livAdmNewInd.table.campaign') }}</th>
                        <th>{{ __('livAdmNewInd.table.preview') }}</th>
                        <th>{{ __('livAdmNewInd.table.status') }}</th>

                        <th class="text-center">{{ __('livAdmNewInd.table.opens') }}</th>
                        <th class="text-center">{{ __('livAdmNewInd.table.uniqueOpens') }}</th>
                        <th class="text-center">{{ __('livAdmNewInd.table.clicks') }}</th>
                        <th class="text-center">{{ __('livAdmNewInd.table.uniqueClicks') }}</th>
                        <th class="text-center">{{ __('livAdmNewInd.table.ctr') }}</th>

                        <th>{{ __('livAdmNewInd.table.createdAt') }}</th>
                        <th>{{ __('livAdmNewInd.table.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($newsletters as $newsletter)
                        @php
                            $isDraft = in_array($newsletter->status, ['draft', 'sending'], true);

                            $s = $stats[$newsletter->id] ?? [
                                'opens' => 0,
                                'unique_opens' => 0,
                                'clicks' => 0,
                                'unique_clicks' => 0,
                                'ctr' => 0.0,
                            ];
                        @endphp

                        <tr>
                            <td>{{ $newsletter->id }}</td>

                            {{-- SUBJECT --}}
                            <td>
                                {{ $newsletter->title_pl ?: '—' }}
                            </td>

                            {{-- KAMPANIA --}}
                            <td>
                                @if ($newsletter->campaign)
                                    <a href="{{ route('admin.campaigns.show', $newsletter->campaign) }}"
                                        class="text-decoration-none fw-semibold">
                                        {{ $newsletter->campaign->topic }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>

                            {{-- PREVIEW --}}
                            <td>
                                {{ $newsletter->preview_text_pl ?: '—' }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if ($newsletter->status === 'sent')
                                    <span class="badge bg-success">
                                        {{ __('livAdmNewInd.status.sent') }}
                                    </span>
                                @elseif ($newsletter->status === 'sending')
                                    <span class="badge bg-warning text-dark">
                                        {{ __('livAdmNewInd.status.sending') }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ __('livAdmNewInd.status.draft') }}
                                    </span>
                                @endif
                            </td>

                            {{-- OPENS --}}
                            <td class="text-center">
                                {{ $isDraft ? '—' : $s['opens'] }}
                            </td>

                            {{-- UNIQUE OPENS --}}
                            <td class="text-center">
                                {{ $isDraft ? '—' : $s['unique_opens'] }}
                            </td>

                            {{-- CLICKS --}}
                            <td class="text-center">
                                {{ $isDraft ? '—' : $s['clicks'] }}
                            </td>

                            {{-- UNIQUE CLICKS --}}
                            <td class="text-center">
                                {{ $isDraft ? '—' : $s['unique_clicks'] }}
                            </td>

                            {{-- CTR --}}
                            <td class="text-center">
                                {{ $isDraft ? '—' : $s['ctr'] . '%' }}
                            </td>

                            {{-- CREATED --}}
                            <td>
                                {{ $newsletter->created_at->format('Y-m-d H:i') }}
                            </td>

                            {{-- ACTIONS --}}
                            <td class="d-flex gap-1">

                                {{-- EDIT --}}
                                @if ($newsletter->status === 'draft')
                                    <a href="{{ route('admin.newsletters.edit', $newsletter) }}"
                                        class="btn btn-sm btn-primary">
                                        ✏️ {{ __('livAdmNewInd.actions.edit') }}
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        ✏️ {{ __('livAdmNewInd.actions.edit') }}
                                    </button>
                                @endif

                                {{-- TEST --}}
                                @if ($newsletter->status === 'draft')
                                    <button wire:click="sendTest({{ $newsletter->id }})"
                                        class="btn btn-sm btn-outline-secondary">
                                        🧪 {{ __('livAdmNewInd.actions.test') }}
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        🧪 {{ __('livAdmNewInd.actions.test') }}
                                    </button>
                                @endif

                                {{-- SEND --}}
                                @if ($newsletter->status === 'draft')
                                    <button wire:click="send({{ $newsletter->id }})" wire:loading.attr="disabled"
                                        class="btn btn-sm btn-outline-success">
                                        📤 {{ __('livAdmNewInd.actions.send') }}
                                    </button>
                                @elseif ($newsletter->status === 'sending')
                                    <button class="btn btn-sm btn-outline-warning" disabled>
                                        ⏳ {{ __('livAdmNewInd.actions.sending') }}
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-success" disabled>
                                        ✅ {{ __('livAdmNewInd.actions.sent') }}
                                    </button>
                                @endif

                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted">
                                {{ __('livAdmNewInd.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <div class="card-footer">
            {{ $newsletters->links() }}
        </div>
    </div>

</div>
