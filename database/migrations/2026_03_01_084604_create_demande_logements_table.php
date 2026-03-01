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
        Schema::create('demande_logements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->timestamp('date_soumission')->useCurrent();
            $table->enum('statut', ['En attente', 'En cours', 'Validée', 'Rejetée'])->default('En attente');
            $table->integer('priorite')->default(0);
            $table->foreignId('administratif_id')->nullable()->constrained('administratifs')->onDelete('set null');
            $table->foreignId('logement_propose_id')->nullable()->constrained('logements')->onDelete('set null');
            $table->text('note_traitement')->nullable();
            $table->timestamp('date_traitement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_logements');
    }
};