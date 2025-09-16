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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 製品名
            $table->text('description')->nullable(); // 製品説明
            $table->integer('price'); // 価格
            $table->integer('stock_quantity')->default(0); // 在庫数量
            $table->integer('product_id')->unique(); // 商品ID
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null'); // カテゴリID
            $table->string('image_path')->nullable(); // 画像パス
            $table->boolean('is_active')->default(true); // アクティブ状態
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
