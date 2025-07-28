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
        Schema::create('quality_log_t2', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quality_id')->constrained('qualities')->onDelete('cascade');
            $table->foreignId('quality_t2_id')->nullable()->constrained('quality_t2');
            $table->boolean('status'); // 'oui/non' will be represented as true/false
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_log_t2');
    }
};
