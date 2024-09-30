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
        Schema::table('one_client_telegram_lines', function (Blueprint $table) {
            // Добавляем колонку source_id
            $table->unsignedBigInteger('source_id')->nullable();
            // Добавляем внешний ключ
            $table->foreign('source_id')->references('id')->on('source_names')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('one_client_telegram_lines', function (Blueprint $table) {
            //
        });
    }
};
