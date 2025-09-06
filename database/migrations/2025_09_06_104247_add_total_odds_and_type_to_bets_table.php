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
        Schema::table('bets', function (Blueprint $table) {
            if (!Schema::hasColumn('bets', 'total_odds')) {
                $table->decimal('total_odds', 10, 2)->nullable()->after('odds');
            }
            if (!Schema::hasColumn('bets', 'type')) {
                $table->string('type')->default('single')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bets', function (Blueprint $table) {
            $table->dropColumn(['total_odds', 'type']);
        });
    }
};
