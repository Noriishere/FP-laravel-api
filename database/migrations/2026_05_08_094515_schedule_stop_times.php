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
        Schema::create('schedule_stop_times', function (Blueprint $table) {

            $table->id();

            $table->foreignId('schedule_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('route_stop_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Estimated Times
            |--------------------------------------------------------------------------
            */

            $table->timestamp('arrival_time')
                ->nullable();

            $table->timestamp('departure_time')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Actual Times (Realtime Tracking)
            |--------------------------------------------------------------------------
            */

            $table->timestamp('actual_arrival_time')
                ->nullable();

            $table->timestamp('actual_departure_time')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Stop Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'arrived',
                'departed',
                'skipped'
            ])->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Order Cache
            |--------------------------------------------------------------------------
            */

            $table->integer('stop_order');

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            $table->integer('delay_minutes')
                ->default(0);

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'schedule_id',
                'route_stop_id'
            ]);

            $table->index([
                'schedule_id',
                'stop_order'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_stop_times');
    }
};