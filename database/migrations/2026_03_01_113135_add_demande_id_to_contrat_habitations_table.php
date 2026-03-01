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
        Schema::table('contrat_habitations', function (Blueprint $table) {
            $table->foreignId('demande_logement_id')->nullable()->constrained('demande_logements')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrat_habitations', function (Blueprint $table) {
        //
        });
    }
};