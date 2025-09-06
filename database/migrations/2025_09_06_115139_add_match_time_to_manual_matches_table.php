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
        Schema::table('manual_matches', function (Blueprint $table) {
            $table->time('match_time')->nullable()->after('league'); // Maçın başlama saati
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manual_matches', function (Blueprint $table) {
            $table->dropColumn('match_time');
        });
    }
};