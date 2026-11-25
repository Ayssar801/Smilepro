<?php

namespace App\Providers;

use App\Models\Gebruiker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.smilepro', 'layouts.praktijk'], function ($view): void {
            $gebruiker = Auth::user();
            $rol = null;
            $home = route('login');

            if ($gebruiker instanceof Gebruiker) {
                $gebruiker->loadMissing('rol');
                $rol = $gebruiker->rol?->Naam;
                $home = route($gebruiker->startroute());
            }

            $view->with([
                'huidigeRol' => $rol,
                'home' => $home,
            ]);
        });
    }
}
