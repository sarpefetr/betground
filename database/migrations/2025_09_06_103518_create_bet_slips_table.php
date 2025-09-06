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
        Schema::create('bet_slips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('match_id');
            $table->string('event_name'); // Maç adı
            $table->string('market_type'); // 1X2, Alt/Üst vs
            $table->string('selection'); // Seçim (home, draw, away, over, under vs)
            $table->string('selection_name'); // Görünen isim
            $table->decimal('odds', 5, 2); // Oran
            $table->string('status')->default('pending'); // pending, won, lost, cancelled
            $table->string('session_id')->nullable(); // Misafir kullanıcılar için
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bet_slips');
    }
};
