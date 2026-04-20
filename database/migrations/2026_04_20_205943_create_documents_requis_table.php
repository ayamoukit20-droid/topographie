<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents_requis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_dossier_id')->constrained('types_dossiers')->onDelete('cascade');
            $table->string('nom', 200);          // ex: 'PV de bornage'
            $table->string('categorie', 50)->default('principal'); // principal | complementaire
            $table->boolean('obligatoire')->default(true);
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents_requis');
    }
};
