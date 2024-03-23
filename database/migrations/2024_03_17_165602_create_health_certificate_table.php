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
        Schema::create('health_certificate', function (Blueprint $table) {  // bằng c2- đến ĐH
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->string('email', 50)->nullable(); // email
            $table->string('phone', 15); // phone
            $table->dateTime('date_of_birth')->nullable(); // ngày sinh
            $table->string('sex', 10); // giois tính
            $table->string('address', 500);  // địa chỉ
            $table->string('height', 10); // chiều cao
            $table->string('weighed', 10); // cân nặng
            $table->string('history_of_pathology', 2000); // tiền sử bệnh lý
            $table->text('text');
            $table->string('image', 200)->nullable(); //// ảnh
            $table->boolean('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_certificate');
    }
};
