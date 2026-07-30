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
       Schema::create('AQUALI_enquete_repondants', function (Blueprint $table) {
    $table->uuid('id')->primary();

    $table->uuid('enquete_id');
    $table->foreign('enquete_id')
          ->references('id')
          ->on('AQUALI_enquetes')
          ->onDelete('cascade');

    $table->uuid('repondant_id');
    $table->foreign('repondant_id')
          ->references('id')
          ->on('AQUALI_repondants')
          ->onDelete('cascade');

    $table->unique(['enquete_id', 'repondant_id']);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AQUALI_enquete_repondants');
    }
};
