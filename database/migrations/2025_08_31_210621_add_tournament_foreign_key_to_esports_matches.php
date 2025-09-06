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
        Schema::table('esports_matches', function (Blueprint $table) {
            $table->foreign('tournament_id')->references('id')->on('esports_tournaments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('esports_matches', function (Blueprint $table) {
            $table->dropForeign(['tournament_id']);
        });
    }
};