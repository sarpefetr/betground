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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manual_match_id')->constrained()->onDelete('cascade');
            $table->enum('team', ['home', 'away']);
            $table->string('scorer')->nullable(); // Gol atan oyuncu
            $table->integer('minute');
            $table->boolean('is_penalty')->default(false);
            $table->boolean('is_own_goal')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};