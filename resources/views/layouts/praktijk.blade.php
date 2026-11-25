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
        <div class="topbar-actions">
            <label class="menu-toggle" for="zijmenu">Menu</label>
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button class="button-ghost" type="submit">Uitloggen</button>
            </form>
        </div>
    </header>
    <input class="menu-state" id="zijmenu" type="checkbox">
    <div class="shell">
        <aside class="sidebar">
            @if ($huidigeRol === 'Beheerder')
                <a class="nav-item is-active" href="{{ route('beheerder.medewerkers') }}" aria-current="page">Medewerkers</a>
            @elseif ($huidigeRol === 'Tandarts')
                <a class="nav-item is-active" href="{{ route('tandarts.dashboard') }}" aria-current="page">Dashboard</a>
            @elseif ($huidigeRol === 'Mondhygiënist')
                <a class="nav-item is-active" href="{{ route('mondhygienist.dashboard') }}" aria-current="page">Dashboard</a>
            @else
                <a class="nav-item {{ request()->routeIs('assistent.patienten', 'assistent.patient') ? 'is-active' : '' }}" href="{{ route('assistent.patienten') }}" @if (request()->routeIs('assistent.patienten', 'assistent.patient')) aria-current="page" @endif>Patiënt zoeken</a>
                <a class="nav-item {{ request()->routeIs('assistent.toevoegen') ? 'is-active' : '' }}" href="{{ route('assistent.toevoegen') }}" @if (request()->routeIs('assistent.toevoegen')) aria-current="page" @endif>Patiënt toevoegen</a>
            @endif
        </aside>
        <main class="workspace">
            @yield('content')
        </main>
    </div>
</body>
</html>
