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
        Schema::create('esports_tournaments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('esports_game_id');
            $table->string('name'); // ESL Pro League, Major Championship
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'finished'])->default('upcoming');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('prize_pool', 15, 2)->nullable();
            $table->string('prize_currency', 3)->default('USD');
            $table->string('organizer')->nullable(); // ESL, BLAST, etc.
            $table->json('teams')->nullable(); // Participating teams
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            
            $table->index('esports_game_id');
            $table->index('slug');
            $table->index('status');
            $table->index('start_date');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esports_tournaments');
    }
};
