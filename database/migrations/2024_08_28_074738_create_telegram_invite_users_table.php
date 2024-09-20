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
        Schema::create('telegram_invite_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->nullable()->constrained('telegram_groups');
            $table->foreignId('phone_id')->nullable()->constrained('telegram_phones');
            $table->boolean('vkl')->default(false);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_invite_users');
    }
};
