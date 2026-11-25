@extends('layouts.praktijk')

@section('title', 'Medewerkers bekijken — SmilePro')

@section('content')
    <section aria-labelledby="page-title">
        <h1 id="page-title">Medewerkers bekijken</h1>
        <p class="lead">Overzicht van medewerkers, rol en status</p>

        <form class="search-form" method="get" action="{{ route('beheerder.medewerkers') }}">
            <div class="search-term">
                <label for="naam">Zoeken op naam</label>
                <input id="naam" name="naam" type="search" value="{{ $naam }}" autocomplete="off">
            </div>
            <div class="search-type">
                <label for="rol">Rol</label>
                <select id="rol" name="rol">
                    <option value="alle" @selected($rol === 'alle')>Alle rollen</option>
                    @foreach ($rollen as $rolNaam)
                        <option value="{{ $rolNaam }}" @selected($rol === $rolNaam)>{{ $rolNaam }}</option>
                    @endforeach
                </select>
            </div>
            <button class="button search-button" type="submit">Filteren</button>
        </form>

        @if ($medewerkers->isEmpty())
            <p class="notice" role="status">Geen medewerkers gevonden.</p>
        @else
            <div class="result-table">
                <table>
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>E-mailadres</th>
                            <th>Rol</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($medewerkers as $medewerker)
                            <tr>
                                <td>{{ $medewerker->volledigeNaam() }}</td>
                                <td>{{ $medewerker->gebruiker->Emailadres }}</td>
                                <td>{{ $medewerker->gebruiker->rol->Naam }}</td>
                                <td>
                                    <span class="status {{ $medewerker->gebruiker->IsActief ? 'is-active' : 'is-inactive' }}">
                                        {{ $medewerker->gebruiker->IsActief ? 'Actief' : 'Inactief' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="result-cards">
                @foreach ($medewerkers as $medewerker)
                    <article class="patient-card">
                        <div>
                            <h2>{{ $medewerker->volledigeNaam() }}</h2>
                            <p>{{ $medewerker->gebruiker->Emailadres }}</p>
                            <p>{{ $medewerker->gebruiker->rol->Naam }}</p>
                        </div>
                        <span class="status {{ $medewerker->gebruiker->IsActief ? 'is-active' : 'is-inactive' }}">
                            {{ $medewerker->gebruiker->IsActief ? 'Actief' : 'Inactief' }}
                        </span>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
