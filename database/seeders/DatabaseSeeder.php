<?php

namespace Database\Seeders;

use App\Models\Gebruiker;
use App\Models\Medewerker;
use App\Models\Patient;
use App\Models\Rol;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public const WACHTWOORD = 'password';

    public function run(): void
    {
        $rollen = [
            'Patiënt' => 'Bekijkt het eigen dossier',
            'Tandarts' => 'Behandelt patiënten',
            'Mondhygiënist' => 'Voert mondhygiëne uit',
            'Assistent' => 'Ondersteunt de praktijk',
            'Beheerder' => 'Beheert SmilePro',
        ];

        foreach ($rollen as $naam => $opmerking) {
            Rol::query()->create([
                'Naam' => $naam,
                'IsActief' => true,
                'Opmerking' => $opmerking,
            ]);
        }

        $this->medewerker('Tandarts', 'Sara', 'Veldman', 'tandarts@smilepro.nl', true);
        $this->medewerker('Tandarts', 'Omar', 'Hassan', 'inactief.tandarts@smilepro.nl', false);
        $this->medewerker('Assistent', 'Noor', 'Bakker', 'assistent@smilepro.nl', true);
        $this->medewerker('Mondhygiënist', 'Lina', 'de Groot', 'mondhygienist@smilepro.nl', true);
        $this->medewerker('Mondhygiënist', 'Tess', 'Mulder', 'inactief.mondhygienist@smilepro.nl', false);
        $this->medewerker('Beheerder', 'Milan', 'Jansen', 'beheerder@smilepro.nl', true);
        $this->medewerker('Beheerder', 'Farid', 'El Amrani', 'inactief.beheerder@smilepro.nl', false);
        $this->medewerker('Assistent', 'Sem', 'Kuipers', 'inactief.assistent@smilepro.nl', false);

        $this->patient('P-10248', 'Jan', 'Jansen', '1988-03-12', 'jan.jansen@example.nl');
        $this->patient('P-10302', 'Eva', 'de Vries', '1995-11-04', 'eva.devries@example.nl');
        $this->patient('P-10417', 'Noah', 'Bakker', '2001-06-21', 'noah.bakker@example.nl');
        $this->patient('P-10521', 'Kim', 'de Boer', '1992-07-30', 'inactief.patient@example.nl', false);
    }

    private function patient(string $nummer, string $voornaam, string $achternaam, string $geboortedatum, string $email, bool $actief = true): void
    {
        $gebruiker = Gebruiker::query()->create([
            'RolId' => Rol::query()->where('Naam', 'Patiënt')->firstOrFail()->Id,
            'Emailadres' => $email,
            'Wachtwoord' => self::WACHTWOORD,
            'IsActief' => $actief,
        ]);

        Patient::query()->create([
            'GebruikerId' => $gebruiker->Id,
            'Patientnummer' => $nummer,
            'Voornaam' => $voornaam,
            'Achternaam' => $achternaam,
            'Geboortedatum' => $geboortedatum,
            'Emailadres' => $email,
            'Telefoonnummer' => '0612345678',
            'Straat' => 'Voorbeeldstraat',
            'Huisnummer' => '1',
            'Postcode' => '3511 AB',
            'Woonplaats' => 'Utrecht',
            'IsActief' => $actief,
        ]);
    }

    private function medewerker(string $rol, string $voornaam, string $achternaam, string $email, bool $actief): void
    {
        $gebruiker = Gebruiker::query()->create([
            'RolId' => Rol::query()->where('Naam', $rol)->firstOrFail()->Id,
            'Emailadres' => $email,
            'Wachtwoord' => self::WACHTWOORD,
            'IsActief' => $actief,
        ]);

        Medewerker::query()->create([
            'GebruikerId' => $gebruiker->Id,
            'Voornaam' => $voornaam,
            'Achternaam' => $achternaam,
            'IsActief' => $actief,
        ]);
    }
}
