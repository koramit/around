<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_record_id')->references('id')->on('case_records');
            $table->unsignedSmallInteger('note_type_id');
            $table->foreign('note_type_id')->references('id')->on('note_types');
            $table->unsignedSmallInteger('attending_staff_id')->nullable();
            $table->foreign('attending_staff_id')->references('id')->on('people');
            $table->nullableMorphs('place');
            $table->jsonb('meta');
            $table->json('form');
            $table->json('report')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->date('date_note');
            $table->index(['date_note', 'status']);
            $table->unsignedInteger('author_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
    }
};
