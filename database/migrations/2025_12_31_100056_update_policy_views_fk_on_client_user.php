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
        Schema::table('policy_views', function (Blueprint $table) {
            // Drop existing FK
            $table->dropForeign(['client_user_id']);

            // Re-add with cascade
            $table->foreign('client_user_id')
                ->references('id')
                ->on('client_users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_views', function (Blueprint $table) {
            $table->dropForeign(['client_user_id']);

            // Restore original FK (no cascade)
            $table->foreign('client_user_id')
                ->references('id')
                ->on('client_users');
        });
    }
};
