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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('category', ['slots', 'casino', 'sports', 'esports', 'virtual']);
            $table->string('type')->nullable();
            $table->string('provider')->nullable();
            $table->string('thumbnail')->nullable();
            $table->decimal('rtp', 5, 2)->nullable();
            $table->decimal('min_bet', 10, 2)->default(1);
            $table->decimal('max_bet', 10, 2)->default(10000);
            $table->boolean('is_live')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('order_index')->default(0);
            $table->timestamps();
            
            $table->index('slug');
            $table->index('category');
            $table->index('is_active');
            $table->index('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
