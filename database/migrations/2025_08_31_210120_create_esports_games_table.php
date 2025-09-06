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
        Schema::create('esports_games', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // CS:GO, League of Legends, Dota 2, etc.
            $table->string('slug')->unique();
            $table->string('short_name', 10); // CS, LOL, D2, VAL
            $table->string('icon')->nullable(); // Game icon/logo
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order_index')->default(0);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esports_games');
    }
};
