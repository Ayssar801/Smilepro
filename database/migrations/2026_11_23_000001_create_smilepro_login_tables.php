<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Rol', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Naam', 50)->unique();
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt')->useCurrent();
            $table->dateTime('DatumGewijzigd')->nullable();
        });

        Schema::create('Gebruiker', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('RolId');
            $table->string('Emailadres', 150)->unique();
            $table->string('Wachtwoord');
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt')->useCurrent();
            $table->dateTime('DatumGewijzigd')->nullable();
            $table->foreign('RolId')->references('Id')->on('Rol');
        });

        Schema::create('Medewerker', function (Blueprint $table) {
            $table->increments('Id');
            $table->unsignedInteger('GebruikerId')->unique();
            $table->string('Voornaam', 100);
            $table->string('Achternaam', 100);
            $table->boolean('IsActief')->default(true);
            $table->string('Opmerking', 255)->nullable();
            $table->dateTime('DatumAangemaakt')->useCurrent();
            $table->dateTime('DatumGewijzigd')->nullable();
            $table->foreign('GebruikerId')->references('Id')->on('Gebruiker');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Medewerker');
        Schema::dropIfExists('Gebruiker');
        Schema::dropIfExists('Rol');
    }
};
