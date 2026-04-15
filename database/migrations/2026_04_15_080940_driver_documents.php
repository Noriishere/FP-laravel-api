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
        Schema::create('driver_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('driver_id')->constrained()->cascadeOnDelete();

            $table->enum('type', ['ktp', 'sim', 'selfie']);

            $table->string('file_path');

            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            $table->text('note')->nullable(); // alasan reject

            $table->timestamps();

            $table->index(['driver_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
