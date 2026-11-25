@extends('layouts.praktijk')

@section('title', 'Patiënt toevoegen — SmilePro')

@section('content')
    <section aria-labelledby="page-title">
        <h1 id="page-title">Patiënt toevoegen</h1>
        <p class="lead">Nieuwe patiënt vastleggen</p>

        @if (session('status'))
            <p class="success" role="status">{{ session('status') }}</p>
        @endif

        <form method="post" action="{{ route('assistent.toevoegen.opslaan') }}">
            @csrf
            <div class="form-grid">
                <div>
                    <label for="voornaam">Voornaam</label>
                    <input id="voornaam" name="voornaam" value="{{ old('voornaam') }}" required>
                    @error('voornaam') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="achternaam">Achternaam</label>
                    <input id="achternaam" name="achternaam" value="{{ old('achternaam') }}" required>
                    @error('achternaam') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="geboortedatum">Geboortedatum</label>
                    <input id="geboortedatum" name="geboortedatum" type="date" value="{{ old('geboortedatum') }}" required>
                    @error('geboortedatum') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="telefoonnummer">Telefoon</label>
                    <input id="telefoonnummer" name="telefoonnummer" value="{{ old('telefoonnummer') }}">
                    @error('telefoonnummer') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="emailadres">E-mailadres</label>
                    <input id="emailadres" name="emailadres" type="email" value="{{ old('emailadres') }}" required>
                    @error('emailadres') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="straat">Straat</label>
                    <input id="straat" name="straat" value="{{ old('straat') }}" required>
                    @error('straat') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="huisnummer">Huisnummer</label>
                    <input id="huisnummer" name="huisnummer" value="{{ old('huisnummer') }}" required>
                    @error('huisnummer') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="postcode">Postcode</label>
                    <input id="postcode" name="postcode" value="{{ old('postcode') }}" placeholder="1234 AB" required>
                    @error('postcode') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="woonplaats">Woonplaats</label>
                    <input id="woonplaats" name="woonplaats" value="{{ old('woonplaats') }}" required>
                    @error('woonplaats') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <button class="button button-inline" type="submit">Patiënt opslaan</button>
        </form>
        <p class="fine-print">Ongeldige of dubbele gegevens worden niet opgeslagen.</p>
    </section>
@endsection
