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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Xpay, Anında Banka, Bitcoin vb.
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['category', 'method'])->default('method'); // Kategori mi yoksa ödeme yöntemi mi
            $table->unsignedBigInteger('parent_id')->nullable(); // Ana kategori (Banka Transferi, Kripto vb.)
            $table->string('method_code')->nullable(); // credit_card, bank_transfer, crypto vb. (backend için)
            $table->string('image')->nullable(); // Kart arkaplan resmi
            $table->decimal('min_amount', 10, 2)->default(50);
            $table->decimal('max_amount', 10, 2)->default(50000);
            $table->decimal('commission_rate', 5, 2)->default(0); // Komisyon oranı %
            $table->string('processing_time')->nullable(); // "0-24 SAAT", "ANLIK" vb.
            $table->json('bank_details')->nullable(); // Banka adı, IBAN, hesap sahibi vb.
            $table->json('form_fields')->nullable(); // Özel form alanları
            $table->text('instructions')->nullable(); // Kullanıcı talimatları
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('order_index')->default(0);
            $table->json('supported_currencies')->nullable(); // Desteklenen para birimleri
            $table->timestamps();
            
            $table->foreign('parent_id')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->index('parent_id');
            $table->index('type');
            $table->index('method_code');
            $table->index('is_active');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
