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
        Schema::create('client', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->string('email', 50)->nullable();
            $table->string('phone', 15);
            $table->string('sex', 10);
            $table->string('address', 500);
            $table->dateTime('date_of_birth')->nullable();
            $table->boolean('trang_thai')->default(1);
            $table->string('password', 255);
            $table->string('rank', 50)->nullable();
            $table->dateTime('last_login_at')->nullable(); // Ngày đăng nhập cuối cùng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client');
    }
};
