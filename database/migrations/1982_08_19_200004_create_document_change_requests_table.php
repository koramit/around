<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_change_requests', function (Blueprint $table) {
            $table->id();
            $table->morphs('changeable');
            $table->unsignedSmallInteger('authority_ability_id');
            $table->jsonb('changes');
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedInteger('requester_id');
            $table->timestamp('submitted_at')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
