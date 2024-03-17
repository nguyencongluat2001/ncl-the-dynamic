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
        Schema::create('degree_education', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);// họ tên
            $table->string('email', 50)->nullable();//email
            $table->string('phone', 15); // sdt
            $table->dateTime('date_of_birth')->nullable(); //ngày sinh
            $table->string('sex', 10); //giới tính
            $table->string('address', 500)->nullable(); //địa chỉ
            $table->string('school', 500)->nullable();  // trường gì
            $table->string('industry', 200)->nullable();  // ngành gì
            $table->dateTime('graduate_time')->nullable();  // tốt nghiệp năm
            $table->string('level', 200)->nullable();  // xếp loại
            $table->string('permanent_residence', 1000)->nullable(); //hộ khẩu thường trú
            $table->string('identity', 20)->nullable(); //căn cước cđ
            $table->dateTime('identity_time')->nullable(); // ngày cấp căn cước 
            $table->string('identity_address')->nullable(); // nơi cấp căn cước 
            $table->string('image',200)->nullable(); // ảnh
            $table->string('image_transfer',200)->nullable(); // ảnh chuyển khoản
            $table->boolean('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('degree_education');
    }
};
