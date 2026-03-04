<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            // Renomme signale_par_id -> etudiant_id (auteur de l'incident)
            $table->renameColumn('signale_par_id', 'etudiant_id');

            // Note de gravité de 0 à 10
            $table->unsignedTinyInteger('gravite')
                ->default(0)
                ->after('description')
                ->comment('Gravité de 0 (mineur) à 10 (très grave)');

            // Qui a rédigé le rapport (admin ou concierge = user_id)
            $table->foreignId('rapporte_par_id')
                ->nullable()
                ->after('gravite')
                ->constrained('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropForeign(['rapporte_par_id']);
            $table->dropColumn(['gravite', 'rapporte_par_id']);
            $table->renameColumn('etudiant_id', 'signale_par_id');
        });
    }
};