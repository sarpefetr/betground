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
        // Drop table if exists to recreate properly
        Schema::dropIfExists('esports_matches');
        
        Schema::create('esports_matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('esports_game_id');
            $table->unsignedBigInteger('tournament_id')->nullable();
            $table->unsignedBigInteger('team1_id');
            $table->unsignedBigInteger('team2_id');
            $table->string('title'); // Match title/description
            $table->enum('format', ['bo1', 'bo3', 'bo5'])->default('bo1'); // Best of format
            $table->enum('status', ['scheduled', 'live', 'finished', 'cancelled'])->default('scheduled');
            $table->datetime('start_time');
            $table->datetime('end_time')->nullable();
            $table->integer('team1_score')->default(0);
            $table->integer('team2_score')->default(0);
            $table->json('odds')->nullable(); // Betting odds
            $table->json('maps_data')->nullable(); // Map results for CS:GO, etc.
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            // Add foreign keys only after all tables exist
            $table->index('esports_game_id');
            $table->index('tournament_id');
            $table->index('team1_id');
            $table->index('team2_id');
            $table->index('status');
            $table->index('start_time');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esports_matches');
    }
};