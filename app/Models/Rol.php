<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['Naam', 'IsActief', 'Opmerking'])]
class Rol extends Model
{
    public const CREATED_AT = 'DatumAangemaakt';

    public const UPDATED_AT = 'DatumGewijzigd';

    protected $table = 'Rol';

    protected $primaryKey = 'Id';

    protected function casts(): array
    {
        return [
            'IsActief' => 'boolean',
        ];
    }

    public function gebruikers(): HasMany
    {
        return $this->hasMany(Gebruiker::class, 'RolId', 'Id');
    }
}
