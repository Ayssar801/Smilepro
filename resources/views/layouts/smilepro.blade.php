<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SmilePro')</title>
    <link rel="stylesheet" href="{{ asset('css/smilepro.css') }}">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ $home }}">SmilePro</a>
        @auth
            @unless (request()->routeIs('login', 'wachtwoord'))
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="button-ghost" type="submit">Uitloggen</button>
                </form>
            @endunless
        @endauth
    </header>
    <main class="page">
        @yield('content')
    </main>
</body>
</html>
