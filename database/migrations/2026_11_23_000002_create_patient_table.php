<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Patient', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('GebruikerId')->nullable()->unique();
            $table->string('Patientnummer', 20)->unique();
            $table->string('Voornaam', 100);
            $table->string('Achternaam', 100);
            $table->date('Geboortedatum');
            $table->string('Telefoonnummer', 20)->nullable();
            $table->string('Emailadres', 150)->unique();
            $table->string('Straat', 100);
            $table->string('Huisnummer', 10);
            $table->string('Postcode', 10);
            $table->string('Woonplaats', 100);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt')->useCurrent();
            $table->dateTime('DatumGewijzigd')->nullable();
            $table->foreign('GebruikerId')->references('Id')->on('Gebruiker');
            $table->index(['Achternaam', 'Voornaam']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Patient');
    }
};
