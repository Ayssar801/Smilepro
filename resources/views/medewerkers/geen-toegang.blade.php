@extends('layouts.smilepro')

@section('title', 'Geen toegang — SmilePro')

@section('content')
    <section class="card" aria-labelledby="page-title">
        <h1 id="page-title">Geen toegang</h1>
        <p class="lead" role="status">Je hebt geen toegang tot de medewerkersgegevens.</p>
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button class="button" type="submit">Uitloggen</button>
        </form>
    </section>
@endsection
