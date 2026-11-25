<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['RolId', 'Emailadres', 'Wachtwoord', 'IsActief', 'Opmerking'])]
#[Hidden(['Wachtwoord'])]
class Gebruiker extends Authenticatable
{
    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    protected $table = 'Gebruiker';

    protected $primaryKey = 'Id';

    public function getAuthIdentifierName(): string
    {
        return 'Id';
    }

    public function getAuthPasswordName(): string
    {
        return 'Wachtwoord';
    }

    protected function casts(): array
    {
        return [
            'IsActief' => 'boolean',
            'Wachtwoord' => 'hashed',
        ];
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'RolId', 'Id');
    }

    public function medewerker(): HasOne
    {
        return $this->hasOne(Medewerker::class, 'GebruikerId', 'Id');
    }

    public function startroute(): string
    {
        return match ($this->rol?->Naam) {
            'Tandarts' => 'tandarts.dashboard',
            'Mondhygiënist' => 'mondhygienist.dashboard',
            'Beheerder' => 'beheerder.medewerkers',
            default => 'assistent.patienten',
        };
    }

    public function weergavenaam(): string
    {
        $medewerker = $this->medewerker;

        if ($medewerker === null) {
            return $this->Emailadres;
        }

        return trim($medewerker->Voornaam.' '.$medewerker->Achternaam);
    }
}
