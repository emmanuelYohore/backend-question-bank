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
       Schema::create('AQUALI_items', function (Blueprint $table) {
    $table->uuid('id')->primary();

    $table->uuid('user_id');
    $table->foreign('user_id')
          ->references('id')
          ->on('AQUALI_users')
          ->onDelete('cascade');

    $table->uuid('format_reponse_id');
    $table->foreign('format_reponse_id')
          ->references('id')
          ->on('AQUALI_format_reponses')
          ->onDelete('cascade');

    $table->string('question', 300);
    $table->integer('min_case_to_check')->nullable();
    $table->integer('max_case_to_check')->nullable();
    $table->boolean('obligatoire')->default(true);
    $table->string('nom_court', 30);
    $table->boolean('archived')->default(false);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AQUALI_items');
    }
};
