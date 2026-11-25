<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class InloggenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_inlogpagina_is_bereikbaar(): void
    {
        $this->get('/inloggen')
            ->assertOk()
            ->assertSee('Inloggen')
            ->assertSee('Log in met je werkaccount');
    }

    public function test_assistent_komt_bij_de_patienten(): void
    {
        $this->inloggen('assistent@smilepro.nl')
            ->assertRedirect(route('assistent.patienten'));

        $this->get(route('assistent.patienten'))
            ->assertOk()
            ->assertSee('Patiënt zoeken');
    }

    public function test_tandarts_komt_op_het_dashboard(): void
    {
        $this->inloggen('tandarts@smilepro.nl')
            ->assertRedirect(route('tandarts.dashboard'));

        $this->get(route('tandarts.dashboard'))
            ->assertOk()
            ->assertSee('Welkom, Sara Veldman')
            ->assertSee('Je bent ingelogd als tandarts.');
    }

    public function test_mondhygienist_komt_op_het_dashboard(): void
    {
        $this->inloggen('mondhygienist@smilepro.nl')
            ->assertRedirect(route('mondhygienist.dashboard'));

        $this->get(route('mondhygienist.dashboard'))
            ->assertOk()
            ->assertSee('Welkom, Lina de Groot')
            ->assertSee('Je bent ingelogd als mondhygiënist.');
    }

    public function test_patient_komt_op_het_dashboard(): void
    {
        $this->inloggen('jan.jansen@example.nl')
            ->assertRedirect(route('patient.dashboard'));

        $this->get(route('patient.dashboard'))
            ->assertOk()
            ->assertSee('Welkom, Jan Jansen')
            ->assertSee('Je bent ingelogd als patiënt.');
    }

    public function test_beheerder_komt_bij_de_medewerkers(): void
    {
        $this->inloggen('beheerder@smilepro.nl')
            ->assertRedirect(route('beheerder.medewerkers'));

        $this->get(route('beheerder.medewerkers'))
            ->assertOk()
            ->assertSee('Medewerkers bekijken');
    }

    public function test_verkeerd_wachtwoord_blokkeert_de_toegang(): void
    {
        $this->from('/inloggen')
            ->post('/inloggen', [
                'emailadres' => 'assistent@smilepro.nl',
                'wachtwoord' => 'fout',
            ])
            ->assertRedirect('/inloggen')
            ->assertSessionHasErrors('emailadres');
    }

    public function test_inactief_account_krijgt_geen_toegang(): void
    {
        $this->from('/inloggen')
            ->post('/inloggen', [
                'emailadres' => 'inactief.tandarts@smilepro.nl',
                'wachtwoord' => DatabaseSeeder::WACHTWOORD,
            ])
            ->assertRedirect('/inloggen')
            ->assertSessionHasErrors('emailadres');
    }

    public function test_wachtwoord_vergeten_verwijst_naar_de_beheerder(): void
    {
        $this->get('/wachtwoord-vergeten')
            ->assertOk()
            ->assertSee('Neem contact op met de beheerder van de praktijk.');
    }

    private function inloggen(string $email): TestResponse
    {
        return $this->post('/inloggen', [
            'emailadres' => $email,
            'wachtwoord' => DatabaseSeeder::WACHTWOORD,
        ]);
    }
}
