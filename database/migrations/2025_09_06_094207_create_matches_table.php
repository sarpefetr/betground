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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->unique(); // API'den gelen unique match ID
            $table->string('sport_key');
            $table->string('sport_title');
            $table->string('league_id')->nullable();
            $table->string('home_team');
            $table->string('away_team');
            $table->timestamp('commence_time');
            $table->boolean('is_live')->default(false);
            $table->integer('home_score')->default(0);
            $table->integer('away_score')->default(0);
            $table->integer('minute')->default(0);
            $table->string('status')->default('scheduled'); // scheduled, live, finished
            
            // Ana bahis oranları
            $table->decimal('odds_home', 5, 2)->nullable();
            $table->decimal('odds_draw', 5, 2)->nullable();
            $table->decimal('odds_away', 5, 2)->nullable();
            
            // Diğer market oranları JSON olarak
            $table->json('additional_markets')->nullable();
            
            $table->timestamps();
            
            $table->index(['sport_key', 'commence_time']);
            $table->index('is_live');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
