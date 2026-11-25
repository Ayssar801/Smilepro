<?php

namespace App\Http\Controllers;

use App\Models\Gebruiker;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PatientZoekenController extends Controller
{
    public function index(Request $request): View
    {
        $this->controleerAssistent();

        $zoekterm = trim((string) $request->query('zoekterm', ''));
        $zoektype = $request->query('zoektype', 'naam') === 'patientnummer' ? 'patientnummer' : 'naam';
        $gezocht = $request->exists('zoekterm');
        $patienten = collect();
        $melding = null;

        if ($gezocht && $zoekterm === '') {
            $melding = 'Vul een naam of patiëntnummer in. Er worden geen patiëntgegevens getoond.';
        } elseif ($gezocht) {
            $patienten = $this->zoek($zoekterm, $zoektype);

            if ($patienten->isEmpty()) {
                $melding = 'Geen patiënt gevonden. Er worden geen patiëntgegevens getoond.';
            }
        }

        return view('patienten.zoeken', [
            'zoekterm' => $zoekterm,
            'zoektype' => $zoektype,
            'gezocht' => $gezocht,
            'patienten' => $patienten,
            'melding' => $melding,
        ]);
    }

    private function controleerAssistent(): void
    {
        $gebruiker = Auth::user();
        abort_unless($gebruiker instanceof Gebruiker, 403);

        $gebruiker->load('rol');
        abort_unless($gebruiker->rol?->Naam === 'Assistent', 403);
    }

    /**
     * @return Collection<int, Patient>
     */
    private function zoek(string $zoekterm, string $zoektype): Collection
    {
        $like = '%'.addcslashes($zoekterm, '%_\\').'%';

        $query = Patient::query()
            ->where('IsActief', true)
            ->orderBy('Achternaam')
            ->orderBy('Voornaam');

        if ($zoektype === 'patientnummer') {
            return $query->where('Patientnummer', 'like', $like)->get();
        }

        $concat = DB::getDriverName() === 'sqlite'
            ? "Voornaam || ' ' || Achternaam"
            : "CONCAT(Voornaam, ' ', Achternaam)";

        return $query
            ->where(function (Builder $builder) use ($like, $concat): void {
                $builder->where('Voornaam', 'like', $like)
                    ->orWhere('Achternaam', 'like', $like)
                    ->orWhereRaw($concat.' like ?', [$like]);
            })
            ->get();
    }
}
