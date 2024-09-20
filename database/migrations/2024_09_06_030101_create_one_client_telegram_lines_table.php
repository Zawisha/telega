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
        Schema::create('one_client_telegram_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('one_search_line')->nullable(); // Добавляем поле для внешнего ключа
            $table->text('desc')->nullable();
            $table->timestamps();
            $table->foreign('one_search_line')->references('id')->on('search_telegram_lines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('one_client_telegram_lines');
    }
};
