<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('login')->unique();
            $table->string('full_name')->unique()->collation(config('database.th_collation'));
            $table->string('password');
            $table->jsonb('profile');
            $table->jsonb('preferences')->default(json_encode([
                'home_page' => 'home',
                'items_per_page' => 15,
                'zen_mode' => false,
                'font_scale_index' => 3,
                'mute' => false,
                'notify_approval_result' => true,
                'auto_subscribe_to_channel' => false,
                'auto_unsubscribe_to_channel' => false,
            ]));
            $table->unsignedSmallInteger('division_id')->default(2);
            $table->foreign('division_id')->references('id')->on('divisions');
            $table->boolean('active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('registry_user', function (Blueprint $table) {
            $table->primary(['user_id', 'registry_id']);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedSmallInteger('registry_id');
            $table->foreign('registry_id')->references('id')->on('registries');
            $table->timestamps();
        });

        Schema::create('abilities', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 80)->unique();
            $table->string('label', 80)->nullable();
            $table->unsignedSmallInteger('registry_id')->nullable();
            $table->foreign('registry_id')->references('id')->on('registries');
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 80)->unique();
            $table->string('label', 80)->nullable();
            $table->unsignedSmallInteger('registry_id')->nullable();
            $table->foreign('registry_id')->references('id')->on('registries');
            $table->timestamps();
        });

        Schema::create('ability_role', function (Blueprint $table) {
            $table->primary(['ability_id', 'role_id']);
            $table->unsignedSmallInteger('ability_id');
            $table->foreign('ability_id')->references('id')->on('abilities');
            $table->unsignedSmallInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->primary(['user_id', 'role_id']);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedSmallInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->timestamps();
        });
    }

    public function down(): void {}
};
