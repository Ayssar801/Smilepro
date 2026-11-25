<?php

namespace Tests\Feature;

use App\Models\Gebruiker;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientToevoegenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_gast_wordt_naar_de_inlogpagina_gestuurd(): void
    {
        $this->get('/assistent/patienten/toevoegen')
            ->assertRedirect(route('login'));
    }

    public function test_andere_rol_mag_geen_patient_toevoegen(): void
    {
        $tandarts = Gebruiker::query()->where('Emailadres', 'tandarts@smilepro.nl')->firstOrFail();

        $this->actingAs($tandarts)
            ->post('/assistent/patienten/toevoegen', $this->geldigeGegevens())
            ->assertForbidden();

        $this->assertSame(3, Patient::query()->count());
    }

    public function test_geldige_gegevens_maken_een_patient_met_nummer(): void
    {
        $this->alsAssistent()
            ->post('/assistent/patienten/toevoegen', $this->geldigeGegevens())
            ->assertRedirect(route('assistent.toevoegen'))
            ->assertSessionHas('status', 'Patiënt opgeslagen. Het patiëntnummer is P-10001.');

        $this->assertDatabaseHas('Patient', [
            'Patientnummer' => 'P-10001',
            'Voornaam' => 'Mila',
            'Achternaam' => 'Smit',
            'Emailadres' => 'mila.smit@example.nl',
            'Postcode' => '3511 AB',
        ]);
    }

    public function test_volgende_patient_krijgt_het_volgende_nummer(): void
    {
        $this->alsAssistent()->post('/assistent/patienten/toevoegen', $this->geldigeGegevens());
        $this->alsAssistent()->post('/assistent/patienten/toevoegen', [
            ...$this->geldigeGegevens(),
            'emailadres' => 'finn.smit@example.nl',
            'voornaam' => 'Finn',
        ])->assertSessionHas('status', 'Patiënt opgeslagen. Het patiëntnummer is P-10002.');
    }

    public function test_ontbrekende_gegevens_worden_niet_opgeslagen(): void
    {
        $this->alsAssistent()
            ->from('/assistent/patienten/toevoegen')
            ->post('/assistent/patienten/toevoegen', [
                ...$this->geldigeGegevens(),
                'voornaam' => '',
            ])
            ->assertRedirect('/assistent/patienten/toevoegen')
            ->assertSessionHasErrors('voornaam');

        $this->assertSame(3, Patient::query()->count());
    }

    public function test_ongeldig_emailadres_wordt_niet_opgeslagen(): void
    {
        $this->alsAssistent()
            ->from('/assistent/patienten/toevoegen')
            ->post('/assistent/patienten/toevoegen', [
                ...$this->geldigeGegevens(),
                'emailadres' => 'geen-email',
            ])
            ->assertSessionHasErrors('emailadres');

        $this->assertSame(3, Patient::query()->count());
    }

    public function test_dubbel_emailadres_wordt_niet_opgeslagen(): void
    {
        $this->alsAssistent()
            ->from('/assistent/patienten/toevoegen')
            ->post('/assistent/patienten/toevoegen', [
                ...$this->geldigeGegevens(),
                'emailadres' => 'jan.jansen@example.nl',
            ])
            ->assertSessionHasErrors(['emailadres' => 'Dit e-mailadres is al in gebruik.']);

        $this->assertSame(3, Patient::query()->count());
    }

    public function test_dubbele_persoon_wordt_niet_opgeslagen(): void
    {
        $this->alsAssistent()
            ->from('/assistent/patienten/toevoegen')
            ->post('/assistent/patienten/toevoegen', [
                ...$this->geldigeGegevens(),
                'voornaam' => 'Jan',
                'achternaam' => 'Jansen',
                'geboortedatum' => '1988-03-12',
                'emailadres' => 'ander@example.nl',
            ])
            ->assertSessionHasErrors(['voornaam' => 'Deze patiënt staat al geregistreerd.']);

        $this->assertSame(3, Patient::query()->count());
    }

    private function alsAssistent(): self
    {
        $assistent = Gebruiker::query()->where('Emailadres', 'assistent@smilepro.nl')->firstOrFail();

        return $this->actingAs($assistent);
    }

    /**
     * @return array<string, string>
     */
    private function geldigeGegevens(): array
    {
        return [
            'voornaam' => 'Mila',
            'achternaam' => 'Smit',
            'geboortedatum' => '1992-05-14',
            'telefoonnummer' => '0612345678',
            'emailadres' => 'mila.smit@example.nl',
            'straat' => 'Voorbeeldstraat',
            'huisnummer' => '12',
            'postcode' => '3511ab',
            'woonplaats' => 'Utrecht',
        ];
    }
}
