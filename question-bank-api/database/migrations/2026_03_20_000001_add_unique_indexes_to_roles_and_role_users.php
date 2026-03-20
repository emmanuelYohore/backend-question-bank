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
        Schema::table('roles', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('role_users', function (Blueprint $table) {
            $table->unique(['user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_users', function (Blueprint $table) {
            $table->dropUnique('role_users_user_id_role_id_unique');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique('roles_name_unique');
        });
    }
};
