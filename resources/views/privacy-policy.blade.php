@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h1 class="mb-4">Polityka prywatności</h1>

            <p>
                Niniejsza polityka prywatności opisuje zasady przetwarzania danych
                osobowych użytkowników serwisu oraz subskrybentów newslettera,
                zgodnie z Rozporządzeniem Parlamentu Europejskiego i Rady (UE) 2016/679
                z dnia 27 kwietnia 2016 r. (RODO).
            </p>

            <h3>1. Administrator danych</h3>
            <p>
                Administratorem danych osobowych jest właściciel serwisu
                <strong>{{ config('app.name') }}</strong>.
            </p>

            <h3>2. Zakres przetwarzanych danych</h3>
            <p>
                W ramach newslettera przetwarzane są następujące dane:
            </p>
            <ul>
                <li>adres e-mail,</li>
                <li>data zapisu i wypisu z newslettera,</li>
                <li>informacje techniczne związane z wysyłką newslettera.</li>
            </ul>

            <h3>3. Cel przetwarzania danych</h3>
            <p>
                Dane osobowe są przetwarzane w celu:
            </p>
            <ul>
                <li>wysyłki newslettera,</li>
                <li>informowania o nowych treściach lub usługach,</li>
                <li>realizacji obowiązków wynikających z przepisów prawa.</li>
            </ul>

            <h3>4. Podstawa prawna przetwarzania</h3>
            <p>
                Dane są przetwarzane na podstawie:
            </p>
            <ul>
                <li>art. 6 ust. 1 lit. a RODO – zgoda osoby, której dane dotyczą,</li>
                <li>art. 6 ust. 1 lit. c RODO – obowiązek prawny administratora.</li>
            </ul>

            <h3>5. Okres przechowywania danych</h3>
            <p>
                Dane osobowe są przechowywane do momentu cofnięcia zgody
                lub zgłoszenia żądania ich usunięcia.
            </p>

            <h3>6. Prawa osoby, której dane dotyczą</h3>
            <p>
                Każdej osobie przysługuje prawo do:
            </p>
            <ul>
                <li>dostępu do swoich danych,</li>
                <li>ich sprostowania,</li>
                <li>usunięcia (prawo do bycia zapomnianym),</li>
                <li>ograniczenia przetwarzania,</li>
                <li>cofnięcia zgody w dowolnym momencie.</li>
            </ul>

            <h3>7. Wypisanie z newslettera</h3>
            <p>
                Subskrybent może w każdej chwili wypisać się z newslettera
                lub zażądać usunięcia danych osobowych poprzez link dostępny
                w stopce każdej wiadomości e-mail.
            </p>

            <h3>8. Kontakt</h3>
            <p>
                W sprawach związanych z ochroną danych osobowych
                można skontaktować się poprzez adres e-mail:
                <strong>{{ config('mail.from.address') }}</strong>.
            </p>

            <p class="mt-4 text-muted">
                Ostatnia aktualizacja: {{ now()->format('d.m.Y') }}
            </p>

        </div>
    </div>
@endsection
