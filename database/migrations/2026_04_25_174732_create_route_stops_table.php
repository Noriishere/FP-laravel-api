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
        Schema::create('route_stops', function (Blueprint $table) {
            $table->id();

            $table->foreignId('route_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('code')->unique();

            $table->string('name');

            $table->text('address')->nullable();

            $table->decimal('lat', 10, 7);

            $table->decimal('lng', 10, 7);

            $table->integer('order');

            $table->boolean('is_pickup')->default(true);

            $table->boolean('is_dropoff')->default(true);

            $table->timestamps();

            $table->index(['route_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_stops');
    }
};