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
        Schema::create('one_client_settings_filters_telegram_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('line_id')->nullable(); // Добавляем поле для внешнего ключа
            $table->integer('filter_id')->nullable();
            $table->foreign('line_id')->references('id')->on('one_client_telegram_lines')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('one_client_settings_filters_telegram_lines');
    }
};
