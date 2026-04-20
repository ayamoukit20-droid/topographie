<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            // Ajouter la FK vers types_dossiers (nullable pour compatibilite avec existants)
            $table->foreignId('type_dossier_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('types_dossiers')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropForeign(['type_dossier_id']);
            $table->dropColumn('type_dossier_id');
        });
    }
};
