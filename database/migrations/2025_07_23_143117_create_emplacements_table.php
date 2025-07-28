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
        Schema::create('emplacements', function (Blueprint $table) {
            $table->id();
            $table->string('code_depot')->nullable();
            $table->string('code_zone');
            $table->string('code_rack');
            $table->string('code_meuble');
            $table->string('code_niveau');
            $table->enum('etat', ['V', 'P']);
            $table->string('type_emplacement')->nullable();
            $table->string('code_stockeur')->nullable();
            $table->string('nom_stockeur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emplacements');
    }
};
