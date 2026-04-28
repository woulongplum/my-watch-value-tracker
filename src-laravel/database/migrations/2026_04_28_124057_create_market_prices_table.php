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
        Schema::create('market_prices', function (Blueprint $table) {
          $table->ulid('id')->primary();
          $table->string('ref_number')->index(); // 型番で検索するためindex
          $table->integer('price');              // 市場価格
          $table->text('model_name');            // 楽天の商品名（長いのでtext）
          $table->text('item_url');              // 商品リンク（長いのでtext）
          $table->text('image_url')->nullable(); // 楽天の画像URL
          $table->string('source')->default('rakuten'); // どこから取ったか
          $table->string('item_condition')->nullable(); // 商品の状態（新品、中古など）
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_prices');
    }
};
