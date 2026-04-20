<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ajouter les nouvelles colonnes
        Schema::table('dossiers', function (Blueprint $table) {
            $table->decimal('lat', 10, 7)->nullable()->after('localisation');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');
            $table->string('type_dossier_new', 50)->nullable()->after('user_id');
        });

        // 2. Migrer les données existantes (convertir ancien enum → nouveau format)
        DB::statement("UPDATE dossiers SET type_dossier_new = CASE
            WHEN type_dossier = 'bornage'      THEN 'immatriculation'
            WHEN type_dossier = 'lotissement'  THEN 'lotissement'
            WHEN type_dossier = 'morcellement' THEN 'morcellement'
            WHEN type_dossier = 'fusion'       THEN 'immatriculation'
            WHEN type_dossier = 'implantation' THEN 'maj'
            WHEN type_dossier = 'autre'        THEN 'immatriculation'
            ELSE type_dossier
        END");

        // 3. Supprimer l'ancienne colonne enum
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropColumn('type_dossier');
        });

        // 4. Renommer la nouvelle colonne
        Schema::table('dossiers', function (Blueprint $table) {
            $table->renameColumn('type_dossier_new', 'type_dossier');
        });

        // 5. Rendre NOT NULL
        Schema::table('dossiers', function (Blueprint $table) {
            $table->string('type_dossier', 50)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng']);
        });

        // Revert type_dossier back to string (enum conversion not reversed to avoid data loss)
        Schema::table('dossiers', function (Blueprint $table) {
            $table->string('type_dossier', 50)->nullable(false)->change();
        });
    }
};
