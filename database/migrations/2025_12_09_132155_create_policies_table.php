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
        Schema::create('policies', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('category_id')->nullable()->constrained('policies_category')->nullOnDelete();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean( 'is_published')->default(false);
            $table->integer('created_by')->comment('admin_id');                  
            $table->integer('updated_by')->comment('admin_id');                  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policies');
    }
};
