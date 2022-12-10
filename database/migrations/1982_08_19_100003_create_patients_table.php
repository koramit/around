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
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hn', 6)->unique();
            $table->boolean('gender')->default(false);
            $table->date('dob')->nullable();
            $table->text('profile')->nullable();
            $table->boolean('alive')->default(true);
            $table->timestamps();
        });

        Schema::create('patient_registry', function (Blueprint $table) {
            $table->primary(['patient_id', 'registry_id']);
            $table->unsignedInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->unsignedSmallInteger('registry_id');
            $table->foreign('registry_id')->references('id')->on('registries');
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
