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
        Schema::create("alert_contact_monitor", function (Blueprint $table) {
            $table->primary(["alert_contact_id", "monitor_id"]);
            $table
                ->foreignId("alert_contact_id")
                ->constrained()
                ->onDelete("cascade");
            $table->foreignId("monitor_id")->constrained()->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("alert_contact_monitor");
    }
};
