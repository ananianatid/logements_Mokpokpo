<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // MySQL nécessite de redéfinir l'ENUM via instruction SQL directe
        DB::statement("ALTER TABLE `incidents` MODIFY COLUMN `type` ENUM(
            'Comportement',
            'Dégradation',
            'Voisinage',
            'Violence',
            'Autre'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `incidents` MODIFY COLUMN `type` ENUM(
            'Panne',
            'Dégât',
            'Voisinage'
        ) NOT NULL");
    }
};