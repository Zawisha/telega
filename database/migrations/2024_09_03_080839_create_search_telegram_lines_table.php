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
        Schema::create('search_telegram_lines', function (Blueprint $table) {
            $table->id(); // Автоматически добавляет столбец 'id'
            $table->unsignedBigInteger('my_client_id')->nullable(); // Добавляем поле для внешнего ключа
            $table->timestamps();
            // Создание внешнего ключа, связывающего 'my_client_id' с 'id' таблицы 'my_clients'
            $table->foreign('my_client_id')->references('id')->on('my_clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_telegram_lines');
    }
};
