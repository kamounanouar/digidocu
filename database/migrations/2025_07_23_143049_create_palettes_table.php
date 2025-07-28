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
        Schema::create('palettes', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('code_famille')->nullable();
            $table->string('libelle_famille')->nullable();
            $table->date('date_limite_vente')->nullable();
            $table->date('date_entree')->nullable();
            $table->string('code_statut')->nullable();
            $table->string('code_support')->nullable();
            $table->foreignId('article_id')->constrained();
            $table->unsignedInteger('quantite_palette')->default(0);
            $table->unsignedInteger('quantite_colis')->default(0);
            $table->unsignedInteger('quantite_uvc')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('palettes');
    }
};
