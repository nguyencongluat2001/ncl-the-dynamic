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
        // Bảng chuyên mục
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id'); // Id
            $table->uuid('parent_id')->nullable(); // ID Cha
            $table->string('name'); // Tên chuyên mục
            $table->string('id_menu'); // id chuyên mục
            $table->string('slug')->nullable(); // Đường dẫn
            $table->string('layout')->nullable(); // Bố cục
            $table->string('icon')->nullable(); // Biểu tượng
            $table->string('category_type')->nullable(); // Loại chuyên mục
            $table->string('owner_code')->nullable(); // Mã đơn vị
            $table->string('is_display_at_home')->nullable(); // Hiển thị trang người dùng
            $table->string('status'); // Trạng thái
            $table->string('order'); // Thứ tự
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
