<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\DemandeLogement;
use App\Models\Etudiant;
use App\Observers\DemandeLogementObserver;
use App\Observers\EtudiantObserver;

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
        DemandeLogement::observe(DemandeLogementObserver::class);
        Etudiant::observe(EtudiantObserver::class);
    }
}