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
        Schema::create('modalite_reponses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('format_reponse_id')->constrained('format_reponses')->onDelete('cascade');
            $table->foreignUuid('item_id')->constrained('items')->onDelete('cascade');
            $table->string('intitule')->nullable()->default('pas d\'intitulé');
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
        Schema::dropIfExists('modalite_reponses');
    }
};
