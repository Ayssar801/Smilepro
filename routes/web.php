<?php

use App\Http\Controllers\InloggenController;
use App\Http\Controllers\MedewerkersBekijkenController;
use App\Http\Controllers\PatientBekijkenController;
use App\Http\Controllers\PatientToevoegenController;
use App\Http\Controllers\PatientZoekenController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/inloggen');

Route::get('/inloggen', [InloggenController::class, 'create'])->name('login');
Route::post('/inloggen', [InloggenController::class, 'store'])->name('login.store');
Route::get('/wachtwoord-vergeten', [InloggenController::class, 'wachtwoordVergeten'])->name('wachtwoord');

Route::middleware('auth')->group(function () {
    Route::post('/uitloggen', [InloggenController::class, 'destroy'])->name('logout');
    Route::get('/tandarts', [InloggenController::class, 'tandarts'])->name('tandarts.dashboard');
    Route::get('/mondhygienist', [InloggenController::class, 'mondhygienist'])->name('mondhygienist.dashboard');
    Route::get('/patient', [InloggenController::class, 'patient'])->name('patient.dashboard');

    Route::get('/assistent/patienten', [PatientZoekenController::class, 'index'])->name('assistent.patienten');
    Route::get('/assistent/patienten/toevoegen', [PatientToevoegenController::class, 'create'])->name('assistent.toevoegen');
    Route::post('/assistent/patienten/toevoegen', [PatientToevoegenController::class, 'store'])->name('assistent.toevoegen.opslaan');
    Route::get('/assistent/patienten/{patientnummer}', [PatientBekijkenController::class, 'show'])
        ->where('patientnummer', 'P-[0-9]{5}')
        ->name('assistent.patient');

    Route::get('/beheerder/medewerkers', [MedewerkersBekijkenController::class, 'index'])->name('beheerder.medewerkers');
});
