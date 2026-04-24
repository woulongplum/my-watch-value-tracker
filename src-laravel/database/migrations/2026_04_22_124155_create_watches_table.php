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
        // --- watches テーブル（ユーザーが所有・追跡する時計） ---
        Schema::create('watches', function (Blueprint $table) {
          $table->ulid('id')->primary();
          $table->foreignUlid('brand_id')->constrained();   // 所属ブランドへのリレーション。データ整合性をDBレベルで保証。
          $table->string('reference_number');  // 型番（例: 126610LN）
          $table->string('serial_number');  // 個体番号（資産管理用）
          $table->string('model_name');  // モデル名（例: Submariner Date）
          $table->integer('purchase_price');  // 購入金額
          $table->date('purchase_date');  // 購入日
          $table->char('currency', 3);  // 通貨単位（例: JPY, USD）
          $table->string('image_path')->nullable();  // 時計の写真へのパス
          $table->softDeletes(); // 論理削除用のカラム。物理的にデータを消さず、削除日時を記録することで、誤削除のリスクを減らす。  
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watches');
    }
};
