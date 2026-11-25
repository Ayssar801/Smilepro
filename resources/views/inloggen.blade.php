@extends('layouts.smilepro')

@section('title', 'Inloggen — SmilePro')

@section('content')
    <section class="card" aria-labelledby="page-title">
        <h1 id="page-title">Inloggen</h1>
        <p class="lead">Log in met je werkaccount</p>

        @if ($errors->any())
            <div class="alert" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ route('login.store') }}">
            @csrf
            <label for="emailadres">Werk-e-mailadres</label>
            <input id="emailadres" name="emailadres" type="email" autocomplete="username" value="{{ old('emailadres') }}" required>

            <label for="wachtwoord">Wachtwoord</label>
            <input id="wachtwoord" name="wachtwoord" type="password" autocomplete="current-password" required>

            <button class="button" type="submit">Inloggen</button>
        </form>

        <p class="fine-print">
            <a href="{{ route('wachtwoord') }}">Wachtwoord vergeten?</a>
        </p>
    </section>
@endsection
