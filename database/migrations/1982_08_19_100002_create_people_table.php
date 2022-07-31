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
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name')->unique()->collation(config('database.th_collation'));
            $table->unsignedSmallInteger('division_id')->default(1);
            $table->unsignedTinyInteger('position')->default(1);
            $table->boolean('active')->default(true);
            $table->index(['position', 'division_id', 'active']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
    }
};
