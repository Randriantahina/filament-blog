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
        Schema::create('check_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitor_id')->constrained()->cascadeOnDelete();
            $table->integer('status_code')->nullable();
            $table->integer('response_time_ms')->nullable();
            $table->text('response_body_snippet')->nullable();
            $table->text('error_message')->nullable();
            $table->boolean('is_up');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_logs');
    }
};
