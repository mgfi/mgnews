<div>
    @if ($newsletters->isEmpty())
        <div class="p-6 text-center text-gray-500">
            Brak newsletterów
        </div>
    @else
        <table>
            @foreach ($newsletters as $newsletter)
                <tr>
                    <td>{{ $newsletter->title ?? '—' }}</td>
                    <td>
                        <a href="#">
                            Edytuj
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="mt-4">
            {{ $newsletters->links() }}
        </div>
    @endif
</div>
