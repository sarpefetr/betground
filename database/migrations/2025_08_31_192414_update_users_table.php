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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('phone');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birth_date');
            $table->string('country', 2)->default('TR')->after('gender');
            $table->string('currency', 3)->default('TRY')->after('country');
            $table->enum('status', ['active', 'suspended', 'banned'])->default('active')->after('currency');
            $table->enum('kyc_status', ['pending', 'verified', 'rejected'])->default('pending')->after('status');
            $table->string('referral_code')->unique()->nullable()->after('kyc_status');
            $table->unsignedBigInteger('referred_by')->nullable()->after('referral_code');
            $table->foreign('referred_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['phone', 'birth_date', 'gender', 'country', 'currency', 'status', 'kyc_status', 'referral_code', 'referred_by']);
        });
    }
};
