<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("maintenance_window_monitor", function (
            Blueprint $table,
        ) {
            $table
                ->foreignId("maintenance_window_id")
                ->constrained()
                ->onDelete("cascade");
            $table->foreignId("monitor_id")->constrained()->onDelete("cascade");
            $table->primary(["maintenance_window_id", "monitor_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("maintenance_window_monitor");
    }
};
