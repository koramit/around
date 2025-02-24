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
        Schema::create('wards', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name')->unique()->collation(config('database.th_collation'));
            $table->string('name_short', 120)->index()->nullable();
            $table->string('name_ref')->unique();
            $table->unsignedSmallInteger('division_id')->default(1);
            $table->foreign('division_id')->references('id')->on('divisions')->constrained();
            $table->boolean('active')->default(true)->index();
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
