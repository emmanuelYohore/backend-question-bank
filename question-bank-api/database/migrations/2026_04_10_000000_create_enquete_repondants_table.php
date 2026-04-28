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
        Schema::create('enquete_repondants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('enquete_id')->constrained('enquetes')->cascadeOnDelete();
            $table->foreignUuid('repondant_id')->constrained('repondants')->cascadeOnDelete();
            $table->timestamps();

            // Unique constraint to prevent duplicate entries
            $table->unique(['enquete_id', 'repondant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquete_repondants');
    }
};
