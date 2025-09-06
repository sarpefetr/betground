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
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('game_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('potential_win', 15, 2);
            $table->decimal('odds', 8, 2)->nullable();
            $table->enum('status', ['pending', 'won', 'lost', 'cancelled', 'refunded'])->default('pending');
            $table->decimal('result', 15, 2)->default(0);
            $table->json('bet_data')->nullable();
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('set null');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
