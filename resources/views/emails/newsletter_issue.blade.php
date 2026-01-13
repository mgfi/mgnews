<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>{{ $subscriber->email }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            width: 100%;
            padding: 20px 0;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            margin: 0 auto;
            padding: 24px;
            border-radius: 6px;
        }
        h1 {
            margin-top: 0;
        }
        .footer {
            margin-top: 32px;
            font-size: 12px;
            color: #666;
        }
        a.unsubscribe {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <h1>{{ $issue->title_pl }}</h1>

            {{-- Treść newslettera --}}
            {!! $html !!}

            <div class="footer">
                <p>
                    Otrzymujesz tę wiadomość, ponieważ zapisałeś się na newsletter.
                </p>

                <p>
                    <a class="unsubscribe" href="{{ url('/unsubscribe/' . $subscriber->unsubscribe_token) }}">
                        Wypisz się z newslettera
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
