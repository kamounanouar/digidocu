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
        Schema::create('prestation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestation_id')->constrained()->onDelete('cascade');
            $table->date('date'); // date de la prestation
            $table->unsignedInteger('quantite')->default(1);
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // opérateur
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestation_logs');
    }
};
