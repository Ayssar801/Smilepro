@extends('layouts.praktijk')

@section('title', 'Patiënt bekijken — SmilePro')

@section('content')
    <section aria-labelledby="page-title">
        <h1 id="page-title">Patiënt bekijken</h1>
        <p class="lead">Gegevens van de geselecteerde patiënt</p>
        <dl class="details">
            <div>
                <dt>Patiëntnummer</dt>
                <dd>{{ $patient->Patientnummer }}</dd>
            </div>
            <div>
                <dt>Naam</dt>
                <dd>{{ $patient->volledigeNaam() }}</dd>
            </div>
            <div>
                <dt>Geboortedatum</dt>
                <dd>{{ $patient->Geboortedatum->format('d-m-Y') }}</dd>
            </div>
            <div>
                <dt>E-mailadres</dt>
                <dd>{{ $patient->Emailadres }}</dd>
            </div>
            <div>
                <dt>Telefoon</dt>
                <dd>{{ $patient->Telefoonnummer ?: 'Niet opgegeven' }}</dd>
            </div>
            <div>
                <dt>Adres</dt>
                <dd>{{ $patient->adres() }}</dd>
            </div>
        </dl>
        <a class="button button-inline" href="{{ route('assistent.patienten') }}">Terug naar overzicht</a>
    </section>
@endsection
