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
        Schema::create('reponses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('repondant_id')->constrained('repondants')->onDelete('cascade');
            $table->foreignUuid('item_id')->constrained('items')->onDelete('cascade');
            $table->foreignUuid('enquete_id')->constrained('enquetes')->onDelete('cascade');
            $table->foreignUuid('modalite_reponse_id')->nullable(true)->constrained('modalite_reponses')->onDelete('cascade');
            $table->string('valeur_texte')->nullable(true);
            $table->string('valeur_evn')->nullable(true);
            $table->timestamps();
        });
    }
    
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reponses');
    }
};
