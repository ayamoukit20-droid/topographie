<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->enum('type_document', ['PV', 'plan', 'tableau', 'rapport', 'contrat', 'autre']);
            $table->string('fichier'); // chemin stockage
            $table->string('taille')->nullable();
            $table->string('extension')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
