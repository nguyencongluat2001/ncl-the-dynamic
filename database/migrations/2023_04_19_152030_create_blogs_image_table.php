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
        Schema::create('blogs_image', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code_blog');
            $table->string('name',200)->nullable();
            $table->string('name_image',200)->nullable();
            $table->string('order_image',10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs_image');
    }
};
