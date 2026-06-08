<?php

use App\Enums\FormatReponseType;
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
        Schema::create('AQUALI_format_reponses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type',array_column(FormatReponseType::cases(), 'value'))->default(FormatReponseType::TEXTE->value);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AQUALI_format_reponses');
    }
};
