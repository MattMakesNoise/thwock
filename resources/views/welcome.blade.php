<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Thwock') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <main class="min-h-screen bg-thwock-light-muted text-thwock-darker dark:bg-thwock-darker dark:text-thwock-light">
            <div class="content flex min-h-screen flex-col justify-center py-12">
                <div class="max-w-lg">
                    <img src="{{ asset('images/thwock-logo.png') }}" alt="{{ config('app.name', 'Thwock') }}" class="mb-8 h-20 w-20">

                    <h1 class="mb-4 text-4xl font-semibold">Thwock</h1>
                    <p class="mb-8 text-lg text-thwock-dark dark:text-thwock-light-muted">
                        Fast golf scoring for your round.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="rounded bg-thwock-primary px-5 py-3 font-semibold text-thwock-light hover:bg-thwock-secondary hover:text-thwock-darker">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="rounded bg-thwock-primary px-5 py-3 font-semibold text-thwock-light hover:bg-thwock-secondary hover:text-thwock-darker">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="rounded border border-thwock-dark/25 px-5 py-3 font-semibold text-thwock-dark hover:border-thwock-primary dark:border-thwock-light-muted/30 dark:text-thwock-light dark:hover:border-thwock-secondary">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
