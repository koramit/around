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
        Schema::create('case_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients')->constrained();
            $table->unsignedSmallInteger('registry_id');
            $table->foreign('registry_id')->references('id')->on('registries')->constrained();
            $table->json('form');
            $table->json('meta');
            $table->unsignedTinyInteger('status')->default(1)->index();
            $table->dateTime('dismissed_at')->nullable();
            $table->dateTime('archived_at')->nullable();
            $table->unsignedInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users')->constrained();
            $table->unsignedInteger('updater_id');
            $table->foreign('updater_id')->references('id')->on('users')->constrained();
            $table->softDeletes();
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
