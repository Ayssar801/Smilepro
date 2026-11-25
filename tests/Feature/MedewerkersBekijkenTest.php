<?php

namespace Tests\Feature;

use App\Models\Gebruiker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedewerkersBekijkenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_gast_wordt_naar_de_inlogpagina_gestuurd(): void
    {
        $this->get('/beheerder/medewerkers')
            ->assertRedirect(route('login'));
    }

    public function test_niet_beheerder_ziet_geen_medewerkersgegevens(): void
    {
        $tandarts = Gebruiker::query()->where('Emailadres', 'tandarts@smilepro.nl')->firstOrFail();

        $this->actingAs($tandarts)
            ->get('/beheerder/medewerkers')
            ->assertOk()
            ->assertSee('Je hebt geen toegang tot de medewerkersgegevens.')
            ->assertDontSee('Sara Veldman')
            ->assertDontSee('tandarts@smilepro.nl')
            ->assertDontSee('beheerder@smilepro.nl');
    }

    public function test_beheerder_ziet_actieve_en_inactieve_medewerkers(): void
    {
        $this->alsBeheerder()
            ->get('/beheerder/medewerkers')
            ->assertOk()
            ->assertSee('Sara Veldman')
            ->assertSee('tandarts@smilepro.nl')
            ->assertSee('Tandarts')
            ->assertSee('Actief')
            ->assertSee('Omar Hassan')
            ->assertSee('Inactief')
            ->assertSee('Lina de Groot')
            ->assertSee('Milan Jansen');
    }

    public function test_filter_op_naam_vernauwt_de_lijst(): void
    {
        $this->alsBeheerder()
            ->get('/beheerder/medewerkers?naam=Sara&rol=alle')
            ->assertOk()
            ->assertSee('Sara Veldman')
            ->assertDontSee('Omar Hassan')
            ->assertDontSee('Lina de Groot')
            ->assertDontSee('mondhygienist@smilepro.nl');
    }

    public function test_filter_op_rol_vernauwt_de_lijst(): void
    {
        $this->alsBeheerder()
            ->get('/beheerder/medewerkers?naam=&rol=Mondhygiënist')
            ->assertOk()
            ->assertSee('Lina de Groot')
            ->assertSee('mondhygienist@smilepro.nl')
            ->assertDontSee('Sara Veldman')
            ->assertDontSee('Milan Jansen')
            ->assertDontSee('beheerder@smilepro.nl');
    }

    public function test_onbekende_naam_toont_geen_medewerkers(): void
    {
        $this->alsBeheerder()
            ->get('/beheerder/medewerkers?naam=Bestaatniet&rol=alle')
            ->assertOk()
            ->assertSee('Geen medewerkers gevonden.')
            ->assertDontSee('Sara Veldman')
            ->assertDontSee('tandarts@smilepro.nl');
    }

    private function alsBeheerder(): self
    {
        $beheerder = Gebruiker::query()->where('Emailadres', 'beheerder@smilepro.nl')->firstOrFail();

        return $this->actingAs($beheerder);
    }
}
