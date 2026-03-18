<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contrat_habitations', function (Blueprint $table) {
            $table->dropColumn([
                'statut_signature_etudiant',
                'statut_signature_administratif',
                'date_signature_etudiant',
                'date_signature_administratif',
            ]);
            $table->boolean('document_signe')->default(false)->after('statut');
        });

        Schema::table('etat_des_lieuxes', function (Blueprint $table) {
            $table->dropColumn([
                'signe_etudiant',
                'signe_concierge',
                'date_signature_etudiant',
                'date_signature_concierge',
            ]);
            $table->boolean('document_signe')->default(false)->after('fichier_pdf_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrat_habitations', function (Blueprint $table) {
            $table->dropColumn('document_signe');
            $table->boolean('statut_signature_etudiant')->default(false);
            $table->boolean('statut_signature_administratif')->default(false);
            $table->timestamp('date_signature_etudiant')->nullable();
            $table->timestamp('date_signature_administratif')->nullable();
        });

        Schema::table('etat_des_lieuxes', function (Blueprint $table) {
            $table->dropColumn('document_signe');
            $table->boolean('signe_etudiant')->default(false);
            $table->boolean('signe_concierge')->default(false);
            $table->timestamp('date_signature_etudiant')->nullable();
            $table->timestamp('date_signature_concierge')->nullable();
        });
    }
};
