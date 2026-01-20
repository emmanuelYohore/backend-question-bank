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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_item_id')->constrained('bank_items')->onDelete('cascade');
            $table->foreignId('format_reponse_id')->constrained('format_reponses')->onDelete('cascade');
            $table->string('question')->nullable(false);
            $table->integer('ordre');
            $table->boolean('obligatoire')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
