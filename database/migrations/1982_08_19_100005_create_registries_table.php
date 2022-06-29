<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registries', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name')->unique();
            $table->string('label')->index()->collation(config('database.th_collation'));
            $table->string('label_eng');
            $table->string('route');
            $table->unsignedSmallInteger('division_id')->default(1);
            $table->foreign('division_id')->references('id')->on('divisions')->constrained();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('patient_registry', function (Blueprint $table) {
            $table->primary(['patient_id', 'registry_id']);
            $table->unsignedInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients')->constrained();
            $table->unsignedSmallInteger('registry_id');
            $table->foreign('registry_id')->references('id')->on('registries')->constrained();
            $table->timestamps();
        });

        Schema::create('registry_user', function (Blueprint $table) {
            $table->primary(['user_id', 'registry_id']);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->constrained();
            $table->unsignedSmallInteger('registry_id');
            $table->foreign('registry_id')->references('id')->on('registries')->constrained();
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
