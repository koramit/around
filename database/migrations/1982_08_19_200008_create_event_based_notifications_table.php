<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_based_notifications', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name');
            $table->string('notification_class_name');
            $table->unsignedSmallInteger('registry_id');
            $table->unsignedSmallInteger('ability_id')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
    }
};
