<div class="container">
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">📨 Newslettery</h3>

        <div class="d-flex gap-2">
            <button wire:click="create" class="btn btn-success">
                ➕ Wyślij nowy newsletter
            </button>

            <button class="btn btn-outline-primary" disabled>
                ➕ Dodaj nową kampanię
            </button>
        </div>
    </div>


    <div class="card">
        <div class="card-body p-0">

            <table class="table table-striped table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Temat</th>
                        <th>Tekst podglądu</th>
                        <th>Status</th>

                        <th class="text-center">Opens</th>
                        <th class="text-center">Uniques</th>
                        <th class="text-center">Clicks</th>
                        <th class="text-center">Uniques</th>
                        <th class="text-center">CTR</th>

                        <th>Utworzony</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($newsletters as $newsletter)
                        <tr>
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


                            <td>{{ $newsletter->id }}</td>

                            <td>
                                {{ $newsletter->title_pl ?: '—' }}
                            </td>

                            <td>
                                {{ is_array($newsletter->content_json) ? count($newsletter->content_json) : 0 }}
                            </td>

                            <td>
                                @if ($newsletter->status === 'sent')
                                    <span class="badge bg-success">wysłany</span>
                                @elseif ($newsletter->status === 'sending')
                                    <span class="badge bg-warning text-dark">sending</span>
                                @else
                                    <span class="badge bg-secondary">draft</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($isDraft)
                                    <span class="text-muted">—</span>
                                @elseif ($s['opens'] === 0)
                                    <span class="text-muted fst-italic">0</span>
                                @else
                                    {{ $s['opens'] }}
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($isDraft)
                                    <span class="text-muted">—</span>
                                @else
                                    {{ $s['unique_opens'] }}
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($isDraft)
                                    <span class="text-muted">—</span>
                                @elseif ($s['clicks'] === 0)
                                    <span class="text-muted fst-italic">0</span>
                                @else
                                    {{ $s['clicks'] }}
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($isDraft)
                                    <span class="text-muted">—</span>
                                @else
                                    {{ $s['unique_clicks'] }}
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($isDraft)
                                    <span class="text-muted">—</span>
                                @else
                                    @php
                                        $ctrClass = match (true) {
                                            $s['ctr'] === 0.0 => 'text-muted',
                                            $s['ctr'] >= 20 => 'text-success fw-bold',
                                            $s['ctr'] >= 10 => 'text-warning fw-semibold',
                                            default => 'text-danger',
                                        };
                                    @endphp

                                    <span class="{{ $ctrClass }}">
                                        {{ $s['ctr'] }}%
                                    </span>
                                @endif
                            </td>


                            <td>
                                {{ $newsletter->created_at->format('Y-m-d H:i') }}
                            </td>

                            <td class="d-flex gap-1">

                                {{-- EDYTUJ --}}
                                @if ($newsletter->status === 'draft')
                                    <a href="{{ route('admin.newsletters.edit', $newsletter) }}"
                                        class="btn btn-sm btn-primary">
                                        ✏️ Edytuj
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        ✏️ Edytuj
                                    </button>
                                @endif

                                {{-- TEST --}}
                                @if ($newsletter->status === 'draft')
                                    <button wire:click="sendTest({{ $newsletter->id }})"
                                        class="btn btn-sm btn-outline-secondary">
                                        🧪 Test
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        🧪 Test
                                    </button>
                                @endif

                                {{-- WYŚLIJ --}}
                                @if ($newsletter->status === 'draft')
                                    <button wire:click="send({{ $newsletter->id }})" wire:loading.attr="disabled"
                                        class="btn btn-sm btn-outline-success">
                                        📤 Wyślij
                                    </button>
                                @elseif ($newsletter->status === 'sending')
                                    <button class="btn btn-sm btn-outline-warning" disabled>
                                        ⏳ SENDING
                                    </button>
                                @elseif ($newsletter->status === 'sent')
                                    <button class="btn btn-sm btn-outline-success" disabled>
                                        ✅ SENT
                                    </button>
                                @endif

                            </td>
                        </tr>
                    @empty

                        <tr>
                            <td colspan="11" class="text-center text-muted">

                                Brak newsletterów.
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
