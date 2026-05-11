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
        Schema::table('my_watches', function (Blueprint $table) {
            // raw_image_url を削除
            $table->dropColumn('raw_image_url');

            // note を image_path の後ろに追加
            $table->text('note')->after('image_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('my_watches', function (Blueprint $table) {
            $table->text('raw_image_url')->nullable();
            $table->dropColumn('note');
        });
    }
};
