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
        Schema::create('manual_matches', function (Blueprint $table) {
            $table->id();
            $table->string('home_team');
            $table->string('away_team');
            $table->string('league')->nullable();
            $table->integer('home_score')->default(0);
            $table->integer('away_score')->default(0);
            $table->integer('current_minute')->default(0);
            $table->integer('start_minute')->default(0); // Başlangıç dakikası
            $table->timestamp('started_at')->nullable(); // Gerçek başlama zamanı
            $table->boolean('is_live')->default(true);
            $table->enum('status', ['pending', 'live', 'finished'])->default('pending');
            $table->json('odds')->nullable(); // Tüm bahis oranları
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_matches');
    }
};