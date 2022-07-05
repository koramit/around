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
        Schema::create('document_change_requests', function (Blueprint $table) {
            $table->id();
            $table->morphs('changeable');
            $table->unsignedSmallInteger('authority_ability_id');
            $table->jsonb('changes');
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedInteger('requester_id');
            $table->unsignedInteger('authority_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('disapproved_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
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
        Schema::dropIfExists('document_change_requests');
    }
};
