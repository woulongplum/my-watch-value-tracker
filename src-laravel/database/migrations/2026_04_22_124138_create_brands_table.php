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
        // --- brands テーブル（時計ブランドの管理） ---
        Schema::create('brands', function (Blueprint $table) {
          $table->ulid('id')->primary(); // 26文字のULID。推測困難かつ時系列順に並ぶため、主キーとして採用。
          $table->string('name')->unique();  // ブランド名（例: Rolex, Omega, Grand Seiko）
          $table->integer('oh_period');  // オーバーホール推奨周期（年単位。例: 5, 10）
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
