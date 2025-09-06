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
        Schema::create('user_bonus_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('bonus_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->decimal('claimed_amount', 15, 2)->nullable(); // Kullanıcının talep ettiği miktar
            $table->decimal('awarded_amount', 15, 2)->nullable(); // Admin'in verdiği miktar
            $table->text('user_message')->nullable(); // Kullanıcının mesajı
            $table->text('admin_message')->nullable(); // Admin'in cevabı
            $table->unsignedBigInteger('processed_by')->nullable(); // Hangi admin işlem yaptı
            $table->timestamp('processed_at')->nullable(); // Ne zaman işlem yapıldı
            $table->json('bonus_data')->nullable(); // Talep anındaki bonus bilgileri
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bonus_id')->references('id')->on('bonuses')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
            $table->index('status');
            $table->index('created_at');
            $table->unique(['user_id', 'bonus_id']); // Bir kullanıcı aynı bonusu sadece bir kez talep edebilir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bonus_claims');
    }
};
