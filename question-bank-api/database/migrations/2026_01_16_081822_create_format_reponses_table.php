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
        Schema::create('format_reponses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');       
            $table->unsignedInteger('nb_min_select')->default(1);
            $table->unsignedInteger('nb_max_select')->default(1);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('format_reponses');
    }
};
