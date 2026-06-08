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
        Schema::create('AQUALI_modalite_reponses', function (Blueprint $table) {
    $table->uuid('id')->primary();

    $table->uuid('format_reponse_id');
    $table->foreign('format_reponse_id')
          ->references('id')
          ->on('AQUALI_format_reponses')
          ->onDelete('cascade');

    $table->uuid('item_id');
    $table->foreign('item_id')
          ->references('id')
          ->on('AQUALI_items')
          ->onDelete('cascade');

    $table->string('intitule', 255)->nullable()->default('pas d\'intitulé');
    $table->unsignedInteger('ordre')->default(0);
    $table->string('min_value')->nullable();
    $table->string('max_value')->nullable();

    $table->timestamps();
});
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AQUALI_modalite_reponses');
    }
};
