<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name')->unique()->collation(config('database.th_collation'));
            $table->string('name_en');
            $table->string('name_en_short', 60)->index();
            $table->string('department', 60);
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void {}
};
