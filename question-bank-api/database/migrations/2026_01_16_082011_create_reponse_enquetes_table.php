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
        Schema::create('reponse_enquetes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enquete_id')->constrained('enquetes')->cascadeOnDelete();
            $table->foreignId('repondant_id')->constrained('repondant')->cascadeOnDelete();
            $table->boolean('complete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reponse_enquetes');
    }
};
