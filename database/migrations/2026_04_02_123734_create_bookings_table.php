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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();

            $table->string('order_id')->unique(); // 🔥 wajib buat pakasir

            $table->integer('total_seat');
            $table->decimal('total_price', 10, 2);

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'expired'
            ])->default('pending');
            $table->string('payment_ref')->nullable(); // id dari pakasir / midtrans
            $table->string('payment_provider')->nullable(); // pakasir, midtrans
            $table->string('payment_method')->nullable();   // qris, bni_va, dll
            $table->timestamp('expired_at')->nullable();  // dari pakasir

            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['schedule_id']);
            $table->index(['order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
