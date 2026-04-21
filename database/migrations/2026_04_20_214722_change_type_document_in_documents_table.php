<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Changer l'enum en string pour accepter les noms dynamiques
            $table->string('type_document')->change();
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Revenir à l'enum (risque de perte de données si les valeurs ne matchent pas)
            $table->enum('type_document', ['PV', 'plan', 'tableau', 'rapport', 'contrat', 'autre'])->change();
        });
    }
};
