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
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Bonus adı
            $table->string('slug')->unique();
            $table->text('description'); // Bonus açıklaması
            $table->enum('bonus_type', ['welcome', 'daily', 'weekly', 'cashback', 'referral', 'vip', 'tournament', 'special'])->default('welcome');
            $table->enum('amount_type', ['percentage', 'fixed'])->default('percentage'); // % mi sabit tutar mı
            $table->decimal('amount_value', 10, 2); // Bonus miktarı (% veya TL)
            $table->decimal('min_deposit', 10, 2)->default(0); // Minimum yatırım şartı
            $table->decimal('max_bonus', 10, 2)->nullable(); // Maksimum bonus limiti
            $table->integer('wagering_requirement')->default(1); // Çevrim şartı (kaç kez çevirmeli)
            $table->datetime('valid_from')->nullable(); // Başlangıç tarihi
            $table->datetime('valid_until')->nullable(); // Bitiş tarihi
            $table->string('image')->nullable(); // Bonus resmi
            $table->text('terms_conditions')->nullable(); // Şartlar ve koşullar
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('usage_limit')->nullable(); // Toplam kullanım limiti (null = sınırsız)
            $table->integer('user_limit')->default(1); // Kullanıcı başına limit
            $table->integer('order_index')->default(0);
            $table->json('countries')->nullable(); // Hangi ülkeler için geçerli
            $table->json('currencies')->nullable(); // Hangi para birimleri için geçerli
            $table->timestamps();
            
            $table->index('slug');
            $table->index('bonus_type');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index(['valid_from', 'valid_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonuses');
    }
};
