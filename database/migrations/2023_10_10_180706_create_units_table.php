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
        Schema::create('units', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code_tinh');
            $table->string('code_huyen')->nullable();
            $table->string('code_xa')->nullable();
            $table->string('name')->nullable();
            $table->string('name_type')->nullable();
            // $table->string('status',5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
