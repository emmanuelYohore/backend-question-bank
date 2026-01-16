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
        Schema::create('reponse_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reponse_enquete_id')->constrained('reponse_enquetes')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('modalite_response_id')->constrained('modalite_responses')->cascadeOnDelete();
            $table->string('valeur_texte')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reponse_items');
    }
};
