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
        Schema::create('admissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('an', 6)->unique();
            $table->unsignedInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('patients')->constrained();
            $table->json('meta')->nullable();
            $table->timestamp('encountered_at')->index()->nullable();
            $table->timestamp('dismissed_at')->index()->nullable();
            $table->unsignedSmallInteger('ward_id')->default(1);
            $table->foreign('ward_id')->references('id')->on('wards')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
};
