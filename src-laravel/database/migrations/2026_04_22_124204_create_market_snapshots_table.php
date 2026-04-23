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
        Schema::create('market_snapshots', function (Blueprint $table) {
          $table->ulid('id')->primary();
        
          // 外部キー：watches.id と連携
          $table->foreignUlid('watch_id')->constrained()->cascadeOnDelete();
        
          $table->integer('jp_market_average');
          $table->float('usd_jpy_rate');
          $table->datetime('fetched_at');
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
