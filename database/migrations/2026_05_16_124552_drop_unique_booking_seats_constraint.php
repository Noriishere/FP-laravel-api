<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->dropUnique(
                'booking_seats_schedule_id_seat_id_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->unique([
                'schedule_id',
                'seat_id'
            ]);
        });
    }
};