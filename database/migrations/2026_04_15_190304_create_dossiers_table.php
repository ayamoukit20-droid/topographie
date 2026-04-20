<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type_dossier', ['bornage', 'lotissement', 'morcellement', 'fusion', 'implantation', 'autre']);
            $table->string('proprietaire');
            $table->text('description')->nullable();
            $table->date('date_creation');
            $table->enum('statut', ['en_cours', 'valide', 'termine'])->default('en_cours');
            $table->string('localisation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};
