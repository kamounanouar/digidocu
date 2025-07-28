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
        Schema::create('quality_t2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quality_t1_id')->constrained('quality_t1')->onDelete('cascade');
            $table->integer('position')->nullable(); // Can be null, or unique per quality_type_id
            $table->string('libel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_t2');
    }
};
