<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rapports_disciplinaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->text('description');
            $table->unsignedTinyInteger('gravite')->default(0)->comment('0-10');
            $table->foreignId('rapporte_par_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['Comportement', 'Violence', 'Dégradation', 'Autre'])->default('Comportement');
            $table->enum('statut', ['Nouveau', 'En cours', 'Clôturé'])->default('Nouveau');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapports_disciplinaires');
    }
};