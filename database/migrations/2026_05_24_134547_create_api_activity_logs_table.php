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
        Schema::create('api_activity_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->uuid('request_id')->nullable();

            $table->string('method');
            $table->text('url');

            $table->integer('status_code');

            $table->integer('duration_ms')->nullable();

            $table->string('ip')->nullable();

            $table->text('user_agent')->nullable();

            $table->json('headers')->nullable();

            $table->json('request_body')->nullable();

            $table->json('response_body')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_activity_logs');
    }
};
