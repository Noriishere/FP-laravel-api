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
            $table->decimal('total_price', 10, 2)->nullable();

            $table->enum('status', [
                'pending',     // baru booking
                'waiting_payment', // QR udah dibuat
                'paid',        // webhook success
                'cancelled',
                'completed'
            ])->default('pending');

            $table->string('payment_method')->nullable(); // qris / va
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
