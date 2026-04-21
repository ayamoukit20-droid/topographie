<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Convertir tous les "valide" en "en_cours"
        Illuminate\Support\Facades\DB::table('dossiers')
            ->where('statut', 'valide')
            ->update(['statut' => 'en_cours']);

        // 2. S'assurer que la colonne est un string simple (au cas où c'était un enum)
        Schema::table('dossiers', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('statut')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pas de retour arrière spécifique nécessaire pour les données
    }
};
