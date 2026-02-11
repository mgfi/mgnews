<div>
    <h1 class="text-xl font-bold mb-4">Newslettery</h1>

    <table class="w-full border">
        <thead>
            <tr class="border-b">
                <th class="text-left p-2">Tytuł</th>
                <th class="text-left p-2">Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($newsletters as $newsletter)
                <tr class="border-b">
                    <td class="p-2">
                        {{ $newsletter->title }}
                    </td>
                    <td class="p-2">
                        <a href="{{ route('operator.newsletters.edit', $newsletter) }}" class="text-blue-600 underline">
                            Edytuj
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
