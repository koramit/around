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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('login')->unique();
            $table->string('full_name')->unique();
            $table->string('password');
            $table->jsonb('profile');
            $table->unsignedSmallInteger('division_id')->default(1);
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('abilities', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 80)->unique();
            $table->string('label', 80)->nullable();
            $table->unsignedSmallInteger('registry_id')->nullable();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 80)->unique();
            $table->string('label', 80)->nullable();
            $table->unsignedSmallInteger('registry_id')->nullable();
            $table->timestamps();
        });

        Schema::create('ability_role', function (Blueprint $table) {
            $table->primary(['ability_id', 'role_id']);
            $table->unsignedSmallInteger('ability_id');
            $table->unsignedSmallInteger('role_id');
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->primary(['user_id', 'role_id']);
            $table->unsignedInteger('user_id');
            $table->unsignedSmallInteger('role_id');
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
        // Schema::dropIfExists('users');
    }
};
