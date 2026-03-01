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
        Schema::create('facture_paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_id')->constrained('contrat_habitations')->onDelete('cascade');
            $table->date('mois_concerne');
            $table->float('montant');
            $table->boolean('est_premier_versement')->default(false);
            $table->enum('statut', ['En attente', 'Payé', 'Rejeté'])->default('En attente');
            $table->timestamp('date_soumission')->useCurrent();
            $table->timestamp('date_validation')->nullable();
            $table->foreignId('comptable_id')->nullable()->constrained('comptables')->onDelete('set null');
            $table->string('recu_url')->nullable();
            $table->text('note_rejet')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facture_paiements');
    }
};