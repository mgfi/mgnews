<div class="container">
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h3 class="mb-4">📨 Newslettery</h3>

    <div class="card">
        <div class="card-body p-0">

            <table class="table table-striped table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Subject</th>
                        <th style="width:80px;">Bloki</th>
                        <th style="width:140px;">Status</th>
                        <th style="width:180px;">Utworzony</th>
                        <th style="width:220px;">Akcje</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($newsletters as $newsletter)
                        <tr>
                            <td>{{ $newsletter->id }}</td>

                            <td>
                                {{ $newsletter->subject ?? '—' }}
                            </td>
                            <td>
                                {{ is_array($newsletter->content_json) ? count($newsletter->content_json) : 0 }}
                            </td>
                            <td>
                                @if ($newsletter->status === 'sent')
                                    <span class="badge bg-success">wysłany</span>
                                @elseif ($newsletter->status === 'scheduled')
                                    <span class="badge bg-warning text-dark">zaplanowany</span>
                                @else
                                    <span class="badge bg-secondary">draft</span>
                                @endif
                            </td>

                            <td>
                                {{ $newsletter->created_at->format('Y-m-d H:i') }}
                            </td>

                            <td class="d-flex gap-1">
                                <a href="{{ route('admin.newsletters.edit', $newsletter) }}"
                                    class="btn btn-sm btn-primary">
                                    ✏️ Edytuj
                                </a>

                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                    🧪 Test
                                </button>

                                <button class="btn btn-sm btn-outline-success" disabled>
                                    📤 Wyślij
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
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
