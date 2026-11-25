<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'GebruikerId',
    'Patientnummer',
    'Voornaam',
    'Achternaam',
    'Geboortedatum',
    'Telefoonnummer',
    'Emailadres',
    'Straat',
    'Huisnummer',
    'Postcode',
    'Woonplaats',
    'IsActief',
    'Opmerking',
])]
class Patient extends Model
{
    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    protected $table = 'Patient';

    protected $primaryKey = 'Id';

    protected function casts(): array
    {
        return [
            'Geboortedatum' => 'date',
            'IsActief' => 'boolean',
        ];
    }

    public function volledigeNaam(): string
    {
        return trim($this->Voornaam.' '.$this->Achternaam);
    }

    public function adres(): string
    {
        return trim($this->Straat.' '.$this->Huisnummer).', '.trim($this->Postcode.' '.$this->Woonplaats);
    }
}
