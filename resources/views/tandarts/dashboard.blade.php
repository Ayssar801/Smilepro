@extends('layouts.smilepro')

@section('title', 'Tandartsdashboard — SmilePro')

@section('content')
    <section class="card card-wide" aria-labelledby="page-title">
        <p class="eyebrow">Tandartsdashboard</p>
        <h1 id="page-title">Welkom, {{ $naam }}</h1>
        <p class="lead">Je bent ingelogd als tandarts.</p>
        <p>Vanuit dit dashboard open je straks de functies die bij jouw rol horen.</p>
    </section>
@endsection
