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
        Schema::create('client_users', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('company_name')->nullable();
            $table->boolean('is_2fa_enabled')->default(true);
            $table->unsignedBigInteger('created_by')->nullable()->change()->comment('admin_id');
            $table->unsignedBigInteger('updated_by')->nullable()->change()->comment('admin_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_users');
    }
};
