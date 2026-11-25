<?php

namespace App\Http\Controllers;

use App\Models\Gebruiker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class InloggenController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $gebruiker = $this->huidigeGebruiker();

        if ($gebruiker !== null) {
            return redirect()->route($gebruiker->startroute());
        }

        return view('inloggen');
    }

    public function store(Request $request): RedirectResponse
    {
        $gegevens = $request->validate([
            'emailadres' => ['required', 'email'],
            'wachtwoord' => ['required', 'string'],
        ], [
            'emailadres.required' => 'Vul je werk-e-mailadres in.',
            'emailadres.email' => 'Vul een geldig werk-e-mailadres in.',
            'wachtwoord.required' => 'Vul je wachtwoord in.',
        ]);

        $gebruiker = Gebruiker::query()
            ->with(['rol', 'medewerker'])
            ->where('Emailadres', $gegevens['emailadres'])
            ->first();

        if ($gebruiker === null || ! Hash::check($gegevens['wachtwoord'], $gebruiker->Wachtwoord)) {
            return $this->geweigerd($request, 'Het e-mailadres of wachtwoord is onjuist.');
        }

        if (! $gebruiker->IsActief || ! $gebruiker->rol?->IsActief) {
            return $this->geweigerd($request, 'Dit account is inactief. Je hebt geen toegang.');
        }

        Auth::login($gebruiker);
        $request->session()->regenerate();

        return redirect()->route($gebruiker->startroute());
    }

    public function tandarts(): View
    {
        return $this->dashboard('Tandarts', 'tandarts.dashboard');
    }

    public function mondhygienist(): View
    {
        return $this->dashboard('Mondhygiënist', 'mondhygienist.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function wachtwoordVergeten(): View
    {
        return view('wachtwoord-vergeten');
    }

    private function dashboard(string $rol, string $view): View
    {
        $gebruiker = $this->huidigeGebruiker();
        abort_unless($gebruiker?->rol?->Naam === $rol, 403);

        return view($view, [
            'naam' => $gebruiker->weergavenaam(),
        ]);
    }

    private function huidigeGebruiker(): ?Gebruiker
    {
        $gebruiker = Auth::user();

        if (! $gebruiker instanceof Gebruiker) {
            return null;
        }

        $gebruiker->loadMissing(['rol', 'medewerker']);

        return $gebruiker;
    }

    private function geweigerd(Request $request, string $melding): RedirectResponse
    {
        return back()
            ->withInput($request->only('emailadres'))
            ->withErrors(['emailadres' => $melding]);
    }
}
