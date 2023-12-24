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
        Schema::create('blogs_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code_blog');
            $table->string('title',1000)->nullable();
            $table->text('decision')->nullable();
            $table->string('rate',5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs_details');
    }
};
