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
        Schema::create('esports_teams', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // FaZe Clan, NAVI, Team Liquid
            $table->string('slug')->unique();
            $table->string('short_name', 10); // FaZe, NAVI, TL
            $table->string('logo')->nullable(); // Team logo
            $table->string('country', 2)->nullable(); // Team country
            $table->text('description')->nullable();
            $table->json('social_links')->nullable(); // Twitter, website, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('country');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esports_teams');
    }
};
