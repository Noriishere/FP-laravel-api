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
        Schema::create('api_crash_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->uuid('request_id')->nullable();

            $table->string('method')->nullable();

            $table->text('url')->nullable();

            $table->integer('status_code')->default(500);

            $table->text('message');

            $table->longText('trace')->nullable();

            $table->json('request_body')->nullable();

            $table->string('ip')->nullable();

            $table->text('user_agent')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_crash_logs');
    }
};
