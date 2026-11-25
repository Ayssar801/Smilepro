@extends('layouts.praktijk')

@section('title', 'Patiënt zoeken — SmilePro')

@section('content')
    <section aria-labelledby="page-title">
        <h1 id="page-title">Patiënt zoeken</h1>
        <p class="lead">Zoek op naam of patiëntnummer</p>

        <form class="search-form" method="get" action="{{ route('assistent.patienten') }}">
            <div class="search-term">
                <label for="zoekterm">Naam of patiëntnummer</label>
                <input id="zoekterm" name="zoekterm" type="search" value="{{ $zoekterm }}" autocomplete="off">
            </div>
            <div class="search-type">
                <label for="zoektype">Zoektype</label>
                <select id="zoektype" name="zoektype">
                    <option value="naam" @selected($zoektype === 'naam')>Naam</option>
                    <option value="patientnummer" @selected($zoektype === 'patientnummer')>Patiëntnummer</option>
                </select>
            </div>
            <button class="button search-button" type="submit">Zoeken</button>
        </form>

        @if ($melding)
            <p class="notice" role="status">{{ $melding }}</p>
        @endif

        @if ($patienten->isNotEmpty())
            <div class="result-table">
                <table>
                    <thead>
                        <tr>
                            <th>Patiëntnummer</th>
                            <th>Naam</th>
                            <th>Geboortedatum</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patienten as $patient)
                            <tr>
                                <td>{{ $patient->Patientnummer }}</td>
                                <td>{{ $patient->volledigeNaam() }}</td>
                                <td>{{ $patient->Geboortedatum->format('d-m-Y') }}</td>
                                <td><a class="action-text" href="{{ route('assistent.patient', $patient->Patientnummer) }}">Bekijken</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="result-cards">
                @foreach ($patienten as $patient)
                    <article class="patient-card">
                        <div>
                            <h2>{{ $patient->volledigeNaam() }}</h2>
                            <p>{{ $patient->Patientnummer }}</p>
                            <p>{{ $patient->Geboortedatum->format('d-m-Y') }}</p>
                        </div>
                        <a class="action-text" href="{{ route('assistent.patient', $patient->Patientnummer) }}">Bekijken</a>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
