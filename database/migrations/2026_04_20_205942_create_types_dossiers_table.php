<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('types_dossiers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();       // ex: 'immatriculation'
            $table->string('nom', 100);                 // ex: 'Immatriculation Fonciere'
            $table->text('description')->nullable();
            $table->string('couleur', 20)->default('blue');  // pour les badges
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('types_dossiers');
    }
};
