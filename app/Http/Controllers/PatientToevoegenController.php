<?php

namespace App\Http\Controllers;

use App\Models\Gebruiker;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PatientToevoegenController extends Controller
{
    public function create(): View
    {
        $this->controleerAssistent();

        return view('patienten.toevoegen');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->controleerAssistent();

        $gegevens = $request->validate([
            'voornaam' => ['required', 'string', 'max:100'],
            'achternaam' => ['required', 'string', 'max:100'],
            'geboortedatum' => ['required', 'date', 'before:today'],
            'telefoonnummer' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+()\\s-]{6,20}$/'],
            'emailadres' => ['required', 'email', 'max:150', 'unique:Patient,Emailadres'],
            'straat' => ['required', 'string', 'max:100'],
            'huisnummer' => ['required', 'string', 'max:10'],
            'postcode' => ['required', 'string', 'regex:/^\\d{4}\\s?[A-Za-z]{2}$/'],
            'woonplaats' => ['required', 'string', 'max:100'],
        ], [
            'voornaam.required' => 'Vul de voornaam in.',
            'achternaam.required' => 'Vul de achternaam in.',
            'geboortedatum.required' => 'Vul de geboortedatum in.',
            'geboortedatum.date' => 'Vul een geldige geboortedatum in.',
            'geboortedatum.before' => 'De geboortedatum moet in het verleden liggen.',
            'telefoonnummer.regex' => 'Vul een geldig telefoonnummer in.',
            'emailadres.required' => 'Vul het e-mailadres in.',
            'emailadres.email' => 'Vul een geldig e-mailadres in.',
            'emailadres.unique' => 'Dit e-mailadres is al in gebruik.',
            'straat.required' => 'Vul de straat in.',
            'huisnummer.required' => 'Vul het huisnummer in.',
            'postcode.required' => 'Vul de postcode in.',
            'postcode.regex' => 'Vul een postcode in als 1234 AB.',
            'woonplaats.required' => 'Vul de woonplaats in.',
        ]);

        $voornaam = trim($gegevens['voornaam']);
        $achternaam = trim($gegevens['achternaam']);
        $email = strtolower($gegevens['emailadres']);
        $postcode = strtoupper(preg_replace('/\\s+/', '', $gegevens['postcode']) ?? '');
        $postcode = substr($postcode, 0, 4).' '.substr($postcode, 4);

        $alGeregistreerd = Patient::query()
            ->where('Voornaam', $voornaam)
            ->where('Achternaam', $achternaam)
            ->whereDate('Geboortedatum', $gegevens['geboortedatum'])
            ->exists();

        if ($alGeregistreerd) {
            return back()
                ->withInput()
                ->withErrors(['voornaam' => 'Deze patiënt staat al geregistreerd.']);
        }

        $patient = DB::transaction(function () use ($gegevens, $voornaam, $achternaam, $email, $postcode): Patient {
            return Patient::query()->create([
                'Patientnummer' => $this->volgendPatientnummer(),
                'Voornaam' => $voornaam,
                'Achternaam' => $achternaam,
                'Geboortedatum' => $gegevens['geboortedatum'],
                'Telefoonnummer' => $gegevens['telefoonnummer'] ?: null,
                'Emailadres' => $email,
                'Straat' => trim($gegevens['straat']),
                'Huisnummer' => trim($gegevens['huisnummer']),
                'Postcode' => $postcode,
                'Woonplaats' => trim($gegevens['woonplaats']),
                'IsActief' => true,
            ]);
        });

        return redirect()
            ->route('assistent.toevoegen')
            ->with('status', 'Patiënt opgeslagen. Het patiëntnummer is '.$patient->Patientnummer.'.');
    }

    private function controleerAssistent(): void
    {
        $gebruiker = Auth::user();
        abort_unless($gebruiker instanceof Gebruiker, 403);

        $gebruiker->load('rol');
        abort_unless($gebruiker->rol?->Naam === 'Assistent', 403);
    }

    private function volgendPatientnummer(): string
    {
        $bezet = Patient::query()->pluck('Patientnummer')->flip();
        $nummer = 10001;

        while ($bezet->has(sprintf('P-%05d', $nummer))) {
            $nummer++;
        }

        return sprintf('P-%05d', $nummer);
    }
}
