<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logements', function (Blueprint $table) {
            $table->id();
            $table->string('numero_chambre');
            $table->foreignId('batiment_id')->constrained('batiments')->onDelete('cascade');
            $table->foreignId('type_logement_id')->constrained('type_logements')->onDelete('cascade');
            $table->enum('statut', ['Disponible', 'Réservé', 'Occupé', 'En maintenance'])->default('Disponible');
            $table->integer('etage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logements');
    }
};