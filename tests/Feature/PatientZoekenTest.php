<?php

namespace Tests\Feature;

use App\Models\Gebruiker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientZoekenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_gast_wordt_naar_de_inlogpagina_gestuurd(): void
    {
        $this->get('/assistent/patienten')
            ->assertRedirect(route('login'));
    }

    public function test_andere_rol_ziet_geen_patienten(): void
    {
        $tandarts = Gebruiker::query()->where('Emailadres', 'tandarts@smilepro.nl')->firstOrFail();

        $this->actingAs($tandarts)
            ->get('/assistent/patienten?zoekterm=Jan&zoektype=naam')
            ->assertForbidden()
            ->assertDontSee('Jan Jansen')
            ->assertDontSee('P-10248');
    }

    public function test_zoeken_op_naam_toont_de_passende_patient(): void
    {
        $this->alsAssistent()
            ->get('/assistent/patienten?zoekterm=Jan%20Jansen&zoektype=naam')
            ->assertOk()
            ->assertSee('P-10248')
            ->assertSee('Jan Jansen')
            ->assertSee('12-03-1988')
            ->assertDontSee('Eva de Vries')
            ->assertDontSee('P-10302');
    }

    public function test_zoeken_op_patientnummer_toont_de_passende_patient(): void
    {
        $this->alsAssistent()
            ->get('/assistent/patienten?zoekterm=P-10302&zoektype=patientnummer')
            ->assertOk()
            ->assertSee('Eva de Vries')
            ->assertSee('P-10302')
            ->assertDontSee('Jan Jansen');
    }

    public function test_lege_zoekterm_toont_geen_persoonsgegevens(): void
    {
        $this->alsAssistent()
            ->get('/assistent/patienten?zoekterm=&zoektype=naam')
            ->assertOk()
            ->assertSee('Vul een naam of patiëntnummer in.')
            ->assertDontSee('Jan Jansen')
            ->assertDontSee('P-10248')
            ->assertDontSee('Eva de Vries');
    }

    public function test_onbekende_zoekterm_toont_geen_persoonsgegevens(): void
    {
        $this->alsAssistent()
            ->get('/assistent/patienten?zoekterm=Bestaatniet&zoektype=naam')
            ->assertOk()
            ->assertSee('Geen patiënt gevonden.')
            ->assertDontSee('Jan Jansen')
            ->assertDontSee('Noah Bakker')
            ->assertDontSee('P-10417');
    }

    private function alsAssistent(): self
    {
        $assistent = Gebruiker::query()->where('Emailadres', 'assistent@smilepro.nl')->firstOrFail();

        return $this->actingAs($assistent);
    }
}
