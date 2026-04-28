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
        Schema::create('my_watches', function (Blueprint $table) {
          $table->ulid('id')->primary();
          $table->foreignUlid('brand_id')->constrained(); 
          $table->string('model_name'); //・・時計のモデル名（例：サブマリーナデイト）
          $table->string('reference_number')->index();  // 時計型番
          $table->string('serial_number')->nullable(); // 個体番号　シリアルナンバー
          $table->integer('purchase_price');           // 購入金額
          $table->date('purchase_date');               // 購入日
          $table->string('image_path')->nullable();    // 【重要】自分で撮った写真のパス
          $table->text('raw_image_url')->nullable();    // ネット上の参考画像URL
          $table->softDeletes();  //資産データなので誤削除防止  
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_watches');
    }
};
