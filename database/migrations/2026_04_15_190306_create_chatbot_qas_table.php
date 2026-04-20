<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_qas', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->text('reponse');
            $table->string('mots_cles'); // séparés par virgule
            $table->string('categorie')->default('general');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_qas');
    }
};
