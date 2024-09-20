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
        Schema::create('not_ready_results', function (Blueprint $table) {
            $table->id();
            $table->text('group_name')->nullable();
            $table->text('message')->nullable();
            $table->text('link')->nullable();
            $table->boolean('peredano')->default(false);
            $table->boolean('used')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('not_ready_results');
    }
};
