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
        Schema::create('AQUALI_reponses', function (Blueprint $table) {
    $table->uuid('id')->primary();

    $table->uuid('repondant_id');
    $table->foreign('repondant_id')
          ->references('id')
          ->on('AQUALI_repondants')
          ->onDelete('cascade');

    $table->uuid('item_id');
    $table->foreign('item_id')
          ->references('id')
          ->on('AQUALI_items')
          ->onDelete('cascade');

    $table->uuid('enquete_id');
    $table->foreign('enquete_id')
          ->references('id')
          ->on('AQUALI_enquetes')
          ->onDelete('cascade');

    $table->uuid('modalite_reponse_id')->nullable();
    $table->foreign('modalite_reponse_id')
          ->references('id')
          ->on('AQUALI_modalite_reponses')
          ->onDelete('cascade');

    $table->string('valeur_texte')->nullable();
    $table->string('valeur_evn')->nullable();

    $table->timestamps();
});
    }
    
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AQUALI_reponses');
    }
};
