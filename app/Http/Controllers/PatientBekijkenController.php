<?php

namespace App\Http\Controllers;

use App\Models\Gebruiker;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PatientBekijkenController extends Controller
{
    public function show(string $patientnummer): View
    {
        $this->controleerAssistent();

        $patient = Patient::query()
            ->where('Patientnummer', $patientnummer)
            ->where('IsActief', true)
            ->first();

        if ($patient === null) {
            return view('patienten.ontbreekt');
        }

        return view('patienten.bekijken', ['patient' => $patient]);
    }

    private function controleerAssistent(): void
    {
        $gebruiker = Auth::user();
        abort_unless($gebruiker instanceof Gebruiker, 403);

        $gebruiker->load('rol');
        abort_unless($gebruiker->rol?->Naam === 'Assistent', 403);
    }
}
