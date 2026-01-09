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
        Schema::table('two_factor_codes', function (Blueprint $table) {
            $table->timestamp('locked_until')->nullable()->after('otp_last_sent_at');
            $table->integer('attempts')->default(0)->nullable()->after('locked_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('two_factor_codes', function (Blueprint $table) {
            $table->dropColumn('locked_until');
            $table->dropColumn('attempts');
        });
    }
};
