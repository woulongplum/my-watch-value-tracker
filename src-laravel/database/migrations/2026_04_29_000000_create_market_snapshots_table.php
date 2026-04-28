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
        // --- market_snapshots テーブル（Go側で取得した市場価格の履歴） ---
        Schema::create('market_snapshots', function (Blueprint $table) {
          $table->ulid('id')->primary();
          $table->foreignUlid('my_watch_id')->constrained('my_watches')->cascadeOnDelete(); // 紐づく時計のID。親（watch）が削除された場合、履歴も自動削除（Cascade）。
          $table->integer('jp_market_average');  // 国内市場の平均相場価格
          $table->float('usd_jpy_rate');  // 取得時点の為替レート
          $table->datetime('fetched_at');  // データ取得日時。相場は常に変動するため、いつの情報かを記録。
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_snapshots');
    }
};
