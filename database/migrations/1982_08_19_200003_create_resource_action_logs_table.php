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
        Schema::create('resource_action_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('loggable');
            $table->unsignedTinyInteger('action');
            $table->jsonb('payload')->nullable();
            $table->unsignedInteger('actor_id');
            $table->timestamp('performed_at')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
