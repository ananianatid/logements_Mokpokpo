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
        Schema::create('etat_des_lieuxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_id')->constrained('contrat_habitations')->onDelete('cascade');
            $table->foreignId('concierge_id')->constrained('concierges')->onDelete('cascade');
            $table->enum('type', ['Entrée', 'Sortie']);
            $table->date('date_execution');
            $table->text('remarques_generales')->nullable();
            $table->string('fichier_pdf_url')->nullable();
            $table->boolean('signe_etudiant')->default(false);
            $table->boolean('signe_concierge')->default(false);
            $table->timestamp('date_signature_etudiant')->nullable();
            $table->timestamp('date_signature_concierge')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etat_des_lieuxes');
    }
};