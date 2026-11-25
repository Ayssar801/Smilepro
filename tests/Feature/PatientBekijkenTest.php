<?php

namespace Tests\Feature;

use App\Models\Gebruiker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientBekijkenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_gast_ziet_geen_patientgegevens(): void
    {
        $this->get('/assistent/patienten/P-10248')
            ->assertRedirect(route('login'));
    }

    public function test_andere_rol_ziet_geen_patientgegevens(): void
    {
        $tandarts = Gebruiker::query()->where('Emailadres', 'tandarts@smilepro.nl')->firstOrFail();

        $this->actingAs($tandarts)
            ->get('/assistent/patienten/P-10248')
            ->assertForbidden()
            ->assertDontSee('Jan Jansen')
            ->assertDontSee('jan.jansen@example.nl');
    }

    public function test_assistent_ziet_de_opgeslagen_gegevens(): void
    {
        $this->alsAssistent()
            ->get('/assistent/patienten/P-10248')
            ->assertOk()
            ->assertSee('P-10248')
            ->assertSee('Jan Jansen')
            ->assertSee('12-03-1988')
            ->assertSee('jan.jansen@example.nl')
            ->assertSee('Voorbeeldstraat 1, 3511 AB Utrecht')
            ->assertDontSee('Dossier')
            ->assertDontSee('Eva de Vries');
    }

    public function test_onbekend_patientnummer_toont_geen_persoonsgegevens(): void
    {
        $this->alsAssistent()
            ->get('/assistent/patienten/P-00000')
            ->assertOk()
            ->assertSee('Deze patiënt is niet gevonden.')
            ->assertDontSee('Jan Jansen')
            ->assertDontSee('jan.jansen@example.nl')
            ->assertDontSee('Eva de Vries')
            ->assertDontSee('Noah Bakker');
    }

    private function alsAssistent(): self
    {
        $assistent = Gebruiker::query()->where('Emailadres', 'assistent@smilepro.nl')->firstOrFail();

        return $this->actingAs($assistent);
    }
}
