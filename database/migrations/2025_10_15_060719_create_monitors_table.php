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
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // General
            $table->string('name');
            $table->string('type');
            $table->string('uptime_status');
            $table->integer('check_interval_minutes')->default(5);
            $table->timestamp('last_checked_at')->nullable();

            // HTTP(s)
            $table->string('url')->nullable();
            $table->string('method')->default('GET');
            $table->text('body')->nullable();
            $table->json('headers')->nullable();

            // Port
            $table->integer('port')->nullable();

            // Keyword
            $table->string('keyword')->nullable();
            $table->boolean('keyword_case_sensitive')->default(false);

            // Heartbeat
            $table->integer('heartbeat_grace_period_in_minutes')->default(5);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitors');
    }
};
