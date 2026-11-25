@extends('layouts.smilepro')

@section('title', 'Wachtwoord vergeten — SmilePro')

@section('content')
    <section class="card" aria-labelledby="page-title">
        <h1 id="page-title">Wachtwoord vergeten</h1>
        <p class="lead">Neem contact op met de beheerder van de praktijk. Die kan een nieuw wachtwoord voor je instellen.</p>
        <p class="fine-print">
            <a href="{{ route('login') }}">Terug naar inloggen</a>
        </p>
    </section>
@endsection
