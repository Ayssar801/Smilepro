@extends('layouts.praktijk')

@section('title', 'Patiënt niet gevonden — SmilePro')

@section('content')
    <section aria-labelledby="page-title">
        <h1 id="page-title">Patiënt bekijken</h1>
        <p class="notice" role="status">Deze patiënt is niet gevonden. Er worden geen patiëntgegevens getoond.</p>
        <a class="button button-inline" href="{{ route('assistent.patienten') }}">Terug naar overzicht</a>
    </section>
@endsection
