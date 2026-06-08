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
      Schema::create('AQUALI_bank_item_items', function (Blueprint $table) {
    $table->uuid('id')->primary();

    $table->uuid('bank_item_id');
    $table->foreign('bank_item_id')
          ->references('id')
          ->on('AQUALI_bank_items')
          ->onDelete('cascade');

    $table->uuid('item_id');
    $table->foreign('item_id')
          ->references('id')
          ->on('AQUALI_items')
          ->onDelete('cascade');

    $table->integer('ordre')->nullable();
    $table->unique(['bank_item_id', 'item_id']);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AQUALI_bank_item_items');
    }
};
