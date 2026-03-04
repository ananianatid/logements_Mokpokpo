<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('distances_prefectures', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('prefecture');
            $table->integer('distance'); // en km
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distances_prefectures');
    }
};