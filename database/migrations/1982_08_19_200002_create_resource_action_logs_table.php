<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_action_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('loggable');
            $table->unsignedTinyInteger('action');
            $table->jsonb('payload')->nullable();
            $table->unsignedInteger('actor_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
};
