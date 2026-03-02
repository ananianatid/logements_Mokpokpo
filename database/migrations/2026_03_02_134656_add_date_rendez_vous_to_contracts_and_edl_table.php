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
            $table->dateTime('date_rendez_vous')->nullable()->after('date_fin');
        });

        Schema::table('etat_des_lieuxes', function (Blueprint $table) {
            $table->dateTime('date_rendez_vous')->nullable()->after('date_execution');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrat_habitations', function (Blueprint $table) {
            $table->dropColumn('date_rendez_vous');
        });

        Schema::table('etat_des_lieuxes', function (Blueprint $table) {
            $table->dropColumn('date_rendez_vous');
        });
    }
};