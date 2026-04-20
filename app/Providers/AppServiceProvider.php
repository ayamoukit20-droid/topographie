<?php

namespace App\Providers;

use App\Models\Dossier;
use App\Policies\DossierPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Enregistrement de la policy
        Gate::policy(Dossier::class, DossierPolicy::class);
    }
}
