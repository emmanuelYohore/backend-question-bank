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
            $table->id();
            $table->foreignId('format_reponse_id')->constrained('format_reponses')->onDelete('cascade');
            $table->string('intitule');
            $table->string('valeur');
            $table->integer('ordre');
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
