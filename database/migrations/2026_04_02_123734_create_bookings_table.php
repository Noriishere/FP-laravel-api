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

            $table->string('order_id')->unique();

            $table->integer('total_seat');
            $table->decimal('total_price', 10, 2);

            $table->enum('status', [
                'pending',
                'paid',
                'cancelled',
                'completed'
            ])->default('pending');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'expired',
                'cancelled'
            ])->default('pending');

            $table->string('payment_provider')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_ref')->nullable();

            $table->timestamp('expired_at')->nullable();

            $table->timestamps(); // 🔥 INI WAJIB
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
