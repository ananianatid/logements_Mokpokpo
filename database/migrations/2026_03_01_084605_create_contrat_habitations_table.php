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
        Schema::create('contrat_habitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('logement_id')->constrained('logements')->onDelete('cascade');
            $table->foreignId('administratif_id')->constrained('administratifs')->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['Brouillon', 'En attente de signature', 'Signé', 'Actif', 'Résilié', 'Expiré'])->default('Brouillon');
            $table->boolean('statut_signature_etudiant')->default(false);
            $table->boolean('statut_signature_administratif')->default(false);
            $table->timestamp('date_signature_etudiant')->nullable();
            $table->timestamp('date_signature_administratif')->nullable();
            $table->string('fichier_contrat_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrat_habitations');
    }
};