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
        Schema::create('completed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->timestamp('completed_at');
            $table->timestamp('created_at');
            $table->timestamp('reserved_at');
            $table->string('output_filename')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completed_jobs');
    }
};