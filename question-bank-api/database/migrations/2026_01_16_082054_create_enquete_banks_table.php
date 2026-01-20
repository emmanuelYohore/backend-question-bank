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
        Schema::create('enquete_banks', function (Blueprint $table) {
            $table->foreignId('enquete_id')->constrained('enquete')->onDelete('cascade');
            $table->foreignId('bank_id')->constrained('bank')->onDelete('cascade');
            $table->primary(['enquete_id', 'bank_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquete_banques');
    }
};
