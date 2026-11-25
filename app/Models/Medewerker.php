<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['GebruikerId', 'Voornaam', 'Achternaam', 'IsActief', 'Opmerking'])]
class Medewerker extends Model
{
    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    protected $table = 'Medewerker';

    protected $primaryKey = 'Id';

    protected function casts(): array
    {
        return [
            'IsActief' => 'boolean',
        ];
    }

    public function volledigeNaam(): string
    {
        return trim($this->Voornaam.' '.$this->Achternaam);
    }

    public function gebruiker(): BelongsTo
    {
        return $this->belongsTo(Gebruiker::class, 'GebruikerId', 'Id');
    }
}
