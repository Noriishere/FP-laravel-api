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
        Schema::table('locations', function (Blueprint $table) {

            $table->decimal('heading', 8, 2)
                ->nullable()
                ->after('speed');

            $table->boolean('is_mocked')
                ->default(false)
                ->after('heading');

            $table->decimal('accuracy', 8, 2)
                ->nullable()
                ->after('is_mocked');
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
