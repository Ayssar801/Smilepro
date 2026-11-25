@extends('layouts.smilepro')

@section('title', 'Patiëntendashboard — SmilePro')

@section('content')
    <section class="card card-wide" aria-labelledby="page-title">
        <p class="eyebrow">Patiëntendashboard</p>
        <h1 id="page-title">Welkom, {{ $naam }}</h1>
        <p class="lead">Je bent ingelogd als patiënt.</p>
        <p>Vanuit dit dashboard open je straks de functies die bij jouw rol horen.</p>
    </section>
@endsection
