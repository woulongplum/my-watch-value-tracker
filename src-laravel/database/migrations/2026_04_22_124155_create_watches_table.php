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
        Schema::create('watches', function (Blueprint $table) {
          $table->ulid('id')->primary();
        
          // 外部キー：brands.id と型を完全に一致させてリレーションを張る
          $table->foreignUlid('brand_id')->constrained(); 
        
          $table->string('reference_number');
          $table->string('serial_number');
          $table->string('model_name');
          $table->integer('purchase_price');
          $table->date('purchase_date');
          $table->char('currency', 3);
          $table->string('image_path')->nullable();
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
