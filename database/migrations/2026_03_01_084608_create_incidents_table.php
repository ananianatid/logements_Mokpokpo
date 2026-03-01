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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logement_id')->constrained('logements')->onDelete('cascade');
            $table->foreignId('signale_par_id')->constrained('etudiants')->onDelete('cascade');
            $table->enum('type', ['Panne', 'Dégât', 'Voisinage']);
            $table->text('description');
            $table->enum('statut', ['Nouveau', 'En cours', 'Résolu'])->default('Nouveau');
            $table->timestamp('date_signalement')->useCurrent();
            $table->foreignId('technicien_id')->nullable()->constrained('techniciens')->onDelete('set null');
            $table->timestamp('date_prise_en_charge')->nullable();
            $table->timestamp('date_resolution')->nullable();
            $table->text('rapport_intervention')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};