<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('provider', 30)->default('ad');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->ipAddress()->nullable();
            $table->string('device');
            $table->unsignedTinyInteger('type')->default(1);
            $table->string('browser');
            $table->string('browser_version');
            $table->string('platform');
            $table->string('platform_version');
            $table->string('robot')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
    }
};
