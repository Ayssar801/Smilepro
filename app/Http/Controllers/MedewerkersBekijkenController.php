<?php

namespace App\Http\Controllers;

use App\Models\Gebruiker;
use App\Models\Medewerker;
use App\Models\Rol;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MedewerkersBekijkenController extends Controller
{
    public function index(Request $request): View
    {
        $gebruiker = Auth::user();
        abort_unless($gebruiker instanceof Gebruiker, 403);
        $gebruiker->load('rol');

        if ($gebruiker->rol?->Naam !== 'Beheerder') {
            return view('medewerkers.geen-toegang');
        }

        $rollen = Rol::query()->orderBy('Naam')->pluck('Naam');
        $naam = trim((string) $request->query('naam', ''));
        $rol = (string) $request->query('rol', 'alle');

        if ($rol !== 'alle' && ! $rollen->contains($rol)) {
            $rol = 'alle';
        }

        return view('medewerkers.overzicht', [
            'medewerkers' => $this->medewerkers($naam, $rol),
            'rollen' => $rollen,
            'naam' => $naam,
            'rol' => $rol,
        ]);
    }

    /**
     * @return Collection<int, Medewerker>
     */
    private function medewerkers(string $naam, string $rol): Collection
    {
        $query = Medewerker::query()
            ->with(['gebruiker.rol'])
            ->orderBy('Achternaam')
            ->orderBy('Voornaam');

        if ($rol !== 'alle') {
            $query->whereHas('gebruiker.rol', function (Builder $builder) use ($rol): void {
                $builder->where('Naam', $rol);
            });
        }

        if ($naam !== '') {
            $like = '%'.addcslashes($naam, '%_\\').'%';
            $concat = DB::getDriverName() === 'sqlite'
                ? "Voornaam || ' ' || Achternaam"
                : "CONCAT(Voornaam, ' ', Achternaam)";

            $query->where(function (Builder $builder) use ($like, $concat): void {
                $builder->where('Voornaam', 'like', $like)
                    ->orWhere('Achternaam', 'like', $like)
                    ->orWhereRaw($concat.' like ?', [$like]);
            });
        }

        return $query->get();
    }
}
