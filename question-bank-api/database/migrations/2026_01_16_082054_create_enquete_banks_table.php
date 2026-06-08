<?php

use App\Enums\ModeType;
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
        Schema::create('AQUALI_enquete_banks', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('enquete_id');
            $table->foreign('enquete_id')
                ->references('id')
                ->on('AQUALI_enquetes')
                ->onDelete('cascade');

            $table->uuid('bank_item_id');
            $table->foreign('bank_item_id')
                ->references('id')
                ->on('AQUALI_bank_items')
                ->onDelete('cascade');

            $table->integer('ordre')->nullable();
            $table->enum('mode', array_column(ModeType::cases(), 'value'))
                ->default(ModeType::SYSTEMATIQUE->value);
            $table->unsignedInteger('nombre_items_aleatoires')->nullable();

            $table->unique(['enquete_id', 'bank_item_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('AQUALI_enquete_banks');
    }
};
